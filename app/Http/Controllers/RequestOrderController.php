<?php

namespace App\Http\Controllers;

use App\Exports\ShippingOrderExport;
use App\Models\Expeditions;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ShippingOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class RequestOrderController extends Controller
{
    public function index()
    {
        return view('request-order.index');
    }

    public function datatable(Request $request)
    {
        $user = auth()->user();
        $kode_dealer = $user->kode_dealer;

        if (empty($kode_dealer)) {
            return response()->json([
                'success' => false,
                'message' => 'Kode dealer tidak ditemukan. Silahkan Mengisi Kode Dealer Anda.',
            ], 500);
        }
        // Check if the request is an AJAX request
        if ($request->ajax()) {
            // $data = ShippingOrder::query();

            // if (!$user->hasRole('main_dealer')) {
            //     $data->where('kode_dealer', $kode_dealer);
            // }

            // $data = $data->get();
            $data = ShippingOrder::select([
                'shipping_order.kode_dealer',
                'shipping_order.id_order',
                'shipping_order.id as id_shipping_order',
                'shipping_order.no_resi',
                'e.name as expedition',
                'd.ahass as dealer',
            ])
                ->leftJoin('expeditions as e', 'shipping_order.id_expedition', '=', 'e.id')
                ->leftJoin('dealers as d', 'shipping_order.kode_dealer', '=', 'd.kode');

            if (!$user->hasRole('main_dealer')) {
                $data->where('shipping_order.kode_dealer', $kode_dealer);
            }

            $data = $data->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    // $editBtn = '<button class="btn btn-sm btn-primary edit-dealer" data-id="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#edit-dealer-modal">Edit</button>';
                    // $deleteBtn = '<button class="btn btn-sm btn-danger delete-dealer" data-id="' . $row->id . '">Delete</button>';
                    // return $editBtn . ' ' . $deleteBtn;
                    $expeditionBtn = '<button class="btn btn-sm btn-primary btn-expedition" data-id_shipping_order="' . $row->id_shipping_order . '" data-id_order="' . $row->id_order . '" data-bs-toggle="modal" data-bs-target="#expedition-modal"><i class="fas fa-truck"></i></button>';
                    return $expeditionBtn;
                })
                ->rawColumns(['actions']) // Ensure HTML in the actions column is not escaped
                ->make(true);
        }
    }

    public function exportExcel(Request $request)
    {
        $user = auth()->user();
        $kode_dealer = $user->kode_dealer;
        try {
            $data = ShippingOrder::select([
                'shipping_order.kode_dealer',
                'shipping_order.id_order',
                'shipping_order.id as id_shipping_order',
                'shipping_order.no_resi',
                'shipping_order.created_at',
                'e.name as expedition', // Alias for clarity
            ])
                ->leftJoin('expeditions as e', 'shipping_order.id_expedition', '=', 'e.id');
                if (!$user->hasRole('main_dealer')) {
                    $data->where('shipping_order.kode_dealer', $kode_dealer);
                }

            $data = $data->get();
            $exportData = $data->map(function ($order, $index) {
                return [
                    'No' => $index + 1,
                    'Kode Dealer' => $order->kode_dealer,
                    'Receipt Number' => $order->no_resi,
                    'Expedition' => $order->expedition,
                    'Order Date' => $order->created_at->format('Y-m-d H:i:s'),
                ];
            });
            $filename = 'awating_orders_' . now()->format('Ymd_His') . '.xlsx';

            return Excel::download(new ShippingOrderExport($exportData), $filename);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Export failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateExpedition(Request $request)
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
        $id_shipping_order = $request->id_shipping_order;
        $data = ShippingOrder::where('id', $id_shipping_order)->first();
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Shipping order not found.',
            ], 404);
        }

        $selectedExpedition = Expeditions::where('id', $data->id_expedition)->first();
        $expedition = Expeditions::orderBy('name')->get();
        return response()->json([
            'success' => true,
            'data' => $data,
            'expedition' => $expedition,
            'selectedExpedition' => $selectedExpedition
        ]);
    }

    public function renderListItem(Request $request)
    {
        $query = DB::table('order_detail as od')
            ->leftJoin('orders as o', 'od.id_order', '=', 'o.id')
            ->leftJoin('dealers as d', 'o.buyer_dealer', '=', 'd.kode') // Tambahkan join dengan tabel dealers
            ->select([
                'od.id',
                'od.no_part',
                'od.kode_dealer',
                'od.qty_order',
                'od.qty_supply',
                'od.product_name',
                'o.buyer_name',
                'o.shipping_address',
                'o.buyer_dealer',
                'd.ahass as dealer_name' // Ambil kolom dealer_name dari tabel dealers
            ])
            ->where('od.id_order', $request->id);

        if (!auth()->user()->hasRole('main_dealer')) {
            $query->where('od.kode_dealer', auth()->user()->kode_dealer);
        }

        $detail_order = $query->get();

        $render_detail_order = '';
        foreach ($detail_order as $item) {
            $render_detail_order .= '<tr>
                                    <td class="text-nowrap text-start">' . $item->no_part . '</td>
                                    <td class="text-nowrap text-start">' . $item->product_name . '</td>
                                    <td class="text-nowrap text-end">' . $item->qty_order . '</td>
                                    <td class="text-nowrap"><input type="number" class="form-control form-control-sm qty_supply text-end" value="' . ($item->qty_supply ?? 0) . '" data-id="' . $item->id . '" id="qty_supply_' . $item->id . '" name="qty_supply[' . $item->id . ']"></td>
                                </tr>';
        }

        return response()->json([
            'success' => true,
            'render_detail_order' => $render_detail_order,
            'detail_order' => $detail_order
        ]);
    }

    public function updateShipping(Request $request)
    {
        $request->validate([
            'id_shipping_order' => 'required|integer',
            'id_expedition' => 'required|integer',
            'no_resi' => 'required|string',
            'id_order' => 'required|integer',
            'qty_supply' => 'required|array',
            'qty_supply.*' => 'required|integer|min:0',
        ]);

        $id_shipping_order = $request->id_shipping_order;
        $id_expedition = $request->id_expedition;
        $no_resi = $request->no_resi;

        ShippingOrder::where('id', $id_shipping_order)->update([
            'id_expedition' => $id_expedition,
            'no_resi' => $no_resi,
        ]);

        $id_order = $request->id_order;
        $qty_supply = $request->qty_supply;

        foreach ($qty_supply as $key => $value) {
            OrderDetail::where('id', $key)->where('id_order', $id_order)->update([
                'qty_supply' => $value,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Shipping order and quantities updated successfully.'
        ]);
    }
}
