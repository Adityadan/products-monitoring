<?php

namespace App\Http\Controllers;

use App\Exports\OrderExport;
use App\Models\Dealer;
use App\Models\DetailProduct;
use App\Models\Expeditions;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductImage;
use App\Models\ShippingOrder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('order.index');
    }

    public function datatable(Request $request)
    {
        // Check if the request is an AJAX request
        if ($request->ajax()) {
            $query = Order::leftJoin('dealers as d', 'orders.buyer_dealer', '=', 'd.kode');
            if (!auth()->user()->hasRole('main_dealer') && !auth()->user()->hasRole('superadmin')) {
                $query->where('buyer_dealer', auth()->user()->kode_dealer);
            }
            $data = $query->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $detailOrderBtn = '<button class="btn btn-sm btn-primary detail-order" data-id="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#detail-modal"><i class="fas fa-info-circle"></i></button>';
                    return $detailOrderBtn;
                })
                ->rawColumns(['actions']) // Ensure HTML in the actions column is not escaped
                ->make(true);
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            $data = Order::query();

            if (!auth()->user()->hasRole('main_dealer') && !auth()->user()->hasRole('superadmin')) {
                $data->where('buyer_dealer', auth()->user()->kode_dealer);
            }

            $data = $data->get();

            $exportData = $data->map(function ($order, $index) {
                return [
                    'No' => $index + 1,
                    'Buyer Dealer' => $order->buyer_dealer,
                    'Buyer Name' => $order->buyer_name,
                    'Phone' => $order->phone,
                    'Shipping Address' => $order->shipping_address,
                    'Order Date' => $order->created_at->format('Y-m-d H:i:s'),
                ];
            });

            $filename = 'orders_' . now()->format('Ymd_His') . '.xlsx';

            return Excel::download(new OrderExport($exportData), $filename);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Export failed: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $id = $request->id;
        // Ambil data pesanan berdasarkan ID shipping order
        $order_detail = OrderDetail::where('id_order', $id)->get();
        $order = Order::where('id', $id)->first();
        $shipping = ShippingOrder::LeftJoin('expeditions as e', 'shipping_order.id_expedition', '=', 'e.id')->where('id_order', $id)->first();


        if ($order_detail->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ]);
        }

        // Ambil informasi dealer berdasarkan kode dealer dari order pertama
        $productDealer = Dealer::where('kode', $order_detail->first()->kode_dealer)->first();

        if (!$productDealer) {
            return response()->json([
                'success' => false,
                'message' => 'Dealer information not found',
            ]);
        }

        $productImage = DetailProduct::where('no_part', $order_detail->first()->no_part)->first();

        // Inisialisasi tampilan HTML untuk daftar pesanan
        $orderList = '';
        $total_qty_supply = 0;
        foreach ($order_detail as $item) {
            $qty_supply = $item->qty_supply ?? 0;
            $total_qty_supply += $qty_supply;
            $orderList .= '
            <div class="row gx-1 mx-0 align-items-center border-bottom border-200">
                <div class="col-6 py-3 px-1">
                    <div class="d-flex align-items-center">
                        <a href="javascript:void(0);">
                            <img class="img-fluid rounded-1 me-3 d-none d-md-block" src="' . ($item->product_image ?? asset('no-image.jpg')) . '" alt="" width="60" />
                        </a>
                        <div class="flex-1">
                            <h5 class="fs-9">
                                <a class="text-900" href="javascript:void(0);">' . e($item->product_name) . '</a>
                            </h5>
                            <h6 class="fs-9">
                                <a class="text-900" href="javascript:void(0);">' . e($productDealer->ahass) . '</a>
                            </h6>
                        </div>
                    </div>
                </div>
                <div class="col-6 py-3 px-1">
                    <div class="row align-items-center">
                        <div class="col-md-3 d-flex justify-content-end justify-content-md-center order-1 order-md-0">
                            <p>' . e($item->qty_order) . '</p>
                        </div>
                        <div class="col-md-3 d-flex justify-content-end justify-content-md-center order-1 order-md-0">
                            <p>' . e($qty_supply) . '</p>
                        </div>
                        <div class="col-md-3 text-end ps-0 order-0 order-md-1 mb-2 mb-md-0 text-600">
                            <p class="mb-0 fs-9">Rp' . number_format($item->price, 0, ',', '.') . '</p>
                        </div>
                        <div class="col-md-3 text-end ps-0 order-0 order-md-1 mb-2 mb-md-0 text-600">
                            <p class="mb-0 fs-9">Rp' . number_format($item->subtotal, 0, ',', '.') . '</p>
                        </div>
                    </div>
                </div>
            </div>';
        }
        // Tambahkan total bagian akhir
        $orderList .= '
        <div class="row fw-bold gx-1 mx-0">
            <div class="col-6 col-md-6 py-2 px-1 text-end text-900">Total</div>
            <div class="col-6 col-md-6 px-0">
                <div class="row gx-1 mx-0">
                    <div class="col-md-3 py-2 px-1 d-none d-md-block text-center" id="total-items">' . e($order_detail->first()->total_items) . '</div>
                    <div class="col-md-3 py-2 px-1 d-none d-md-block text-center" id="total-qty-supply">' . e($total_qty_supply) . '</div>
                    <div class="col-12 col-md-3 text-end py-2 px-1" id="total">Rp' . number_format($order_detail->first()->total_price, 0, ',', '.') . '</div>
                </div>
            </div>
        </div>';
        // Kembalikan response JSON
        return response()->json([
            'success' => true,
            'orderList' => $orderList,
            'order' => $order,
            'shipping' => $shipping
        ]);
    }

    /* public function updateExpedition(Request $request)
    {
        $id = $request->id;
        $id_expedition = $request->id_expedition;
        $no_resi = $request->no_resi;

        $order = Order::where('id_detail_order', $id)->first();

        if ($order) {
            // Update existing expedition data
            $order->update([
                'id_expedition' => $id_expedition,
                'no_resi' => $no_resi,
            ]);
            $message = 'Expedition updated successfully';
        } else {
            // Insert new expedition data
            Order::create([
                'id_detail_order' => $id,
                'id_expedition' => $id_expedition,
                'no_resi' => $no_resi,
            ]);
            $message = 'Expedition inserted successfully';
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function editExpedition(Request $request)
    {
        $id = $request->id;
        $data = OrderDetail::with('shippingOrder')->where('id_order', $id)->first();

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Order detail not found.',
            ], 404);
        }

        $selectedExpedition = Expeditions::where('id', $data->id_expedition)->first();
        $expedition = Expeditions::all();

        return response()->json([
            'success' => true,
            'data' => $data,
            'expedition' => $expedition,
            'selectedExpedition' => $selectedExpedition
        ]);
    } */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
