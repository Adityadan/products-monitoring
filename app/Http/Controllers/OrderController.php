<?php

namespace App\Http\Controllers;

use App\Models\Dealer;
use App\Models\Expeditions;
use App\Models\Order;
use App\Models\ProductImage;
use App\Models\ShippingOrder;
use Illuminate\Http\Request;
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
            $data = ShippingOrder::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    // $editBtn = '<button class="btn btn-sm btn-primary edit-dealer" data-id="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#edit-dealer-modal">Edit</button>';
                    // $deleteBtn = '<button class="btn btn-sm btn-danger delete-dealer" data-id="' . $row->id . '">Delete</button>';
                    // return $editBtn . ' ' . $deleteBtn;
                    $expeditionBtn = '<button class="btn btn-sm btn-primary btn-expedition" data-id="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#expedition-modal">Expedition</button>';
                    $detailOrderBtn = '<button class="btn btn-sm btn-primary detail-order" data-id="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#detail-modal">Detail Order</button>';
                    return $expeditionBtn . ' ' . $detailOrderBtn;
                })
                ->rawColumns(['actions']) // Ensure HTML in the actions column is not escaped
                ->make(true);
        }
    }

    // public function showDetail (Request $request) {
    //     $id = $request->id;
    //     $order = Order::find($id);
    //     return response()->json([
    //         'order' => $order
    //     ]);
    // }
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
        $orders = Order::where('id_shipping_order', $id)->get();

        if ($orders->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ]);
        }

        // Ambil informasi dealer berdasarkan kode dealer dari order pertama
        $productDealer = Dealer::where('kode', $orders->first()->kode_dealer)->first();

        if (!$productDealer) {
            return response()->json([
                'success' => false,
                'message' => 'Dealer information not found',
            ]);
        }

        $productImage = ProductImage::where('no_part', $orders->first()->no_part)->first();

        // Inisialisasi tampilan HTML untuk daftar pesanan
        $orderList = '';
        foreach ($orders as $item) {
            $orderList .= '
            <div class="row gx-x1 mx-0 align-items-center border-bottom border-200">
                <div class="col-6 py-3 px-x1">
                    <div class="d-flex align-items-center">
                        <a href="javascript:void(0);">
                            <img class="img-fluid rounded-1 me-3 d-none d-md-block" src="' . ($item->product_image ?? '') . '" alt="" width="60" />
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
                <div class="col-6 py-3 px-x1">
                    <div class="row align-items-center">
                        <div class="col-md-4 d-flex justify-content-end justify-content-md-center order-1 order-md-0">
                            <p>' . e($item->quantity) . '</p>
                        </div>
                        <div class="col-md-4 text-end ps-0 order-0 order-md-1 mb-2 mb-md-0 text-600">
                            <p class="mb-0 fs-9">Rp' . number_format($item->price, 0, ',', '.') . '</p>
                        </div>
                        <div class="col-md-4 text-end ps-0 order-0 order-md-1 mb-2 mb-md-0 text-600">
                            <p class="mb-0 fs-9">Rp' . number_format($item->subtotal, 0, ',', '.') . '</p>
                        </div>
                    </div>
                </div>
            </div>';
        }

        // Tambahkan total bagian akhir
        $orderList .= '
        <div class="row fw-bold gx-x1 mx-0">
            <div class="col-6 col-md-6 py-2 px-x1 text-end text-900">Total</div>
            <div class="col-6 col-md-6 px-0">
                <div class="row gx-x1 mx-0">
                    <div class="col-md-4 py-2 px-x1 d-none d-md-block text-center" id="total-items">' . e($orders->first()->total_items) . '</div>
                    <div class="col-12 col-md-4 text-end py-2 px-x1"></div>
                    <div class="col-12 col-md-4 text-end py-2 px-x1" id="total">Rp' . number_format($orders->first()->total_price, 0, ',', '.') . '</div>
                </div>
            </div>
        </div>';

        // Kembalikan response JSON
        return response()->json([
            'success' => true,
            'orderList' => $orderList,
        ]);
    }

    public function updateExpedition(Request $request)
    {
        $id = $request->id;
        $id_expedition = $request->id_expedition;
        $no_resi = $request->no_resi;

        Order::where('id_shipping_order', $id)->update([
            'id_expedition' => $id_expedition,
            'no_resi' => $no_resi,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Expedition updated successfully'
        ]);
    }

    public function editExpedition(Request $request)
    {
        $id = $request->id;
        $data = Order::where('id_shipping_order', $id)->first();
        $expedition = Expeditions::all();
        $selectedExpedition = Expeditions::where('id', $data->id_expedition)->first();

        return response()->json([
            'success' => true,
            'data' => $data,
            'expedition' => $expedition,
            'selectedExpedition' => $selectedExpedition
        ]);
    }

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
