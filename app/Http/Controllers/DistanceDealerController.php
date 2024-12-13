<?php

namespace App\Http\Controllers;

use App\Models\Dealer;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DistanceDealerController extends Controller
{
    public function index()
    {
        return view('distance-dealer.index');
    }

    public function datatable(Request $request)
    {
        // Check if the request is an AJAX request
        if ($request->ajax()) {
            $dealers = Dealer::select(['id', 'kode', 'ahass', 'kota_kab', 'kecamatan', 'status', 'se_area', 'group','order_distance'])->orderBy('order_distance', 'asc');
            return DataTables::of($dealers)
                ->addIndexColumn()
                ->addColumn('order_distance', function ($row) {
                    $value = isset($row->order_distance) ? $row->order_distance : '0';
                    return '<input type="text" class="form-control form-control-sm text-center order-distance" data-id="' . $row->id . '" id="dealer_' . $row->id . '" name="dealer[' . $row->id . ']" value="' . $value . '">';
                })
                ->rawColumns(['order_distance']) // Ensure HTML in the order_distance column is not escaped
                ->make(true);
        }
    }

    public function update(Request $request, $id)
    {
        $dealer = Dealer::findOrFail($id);

        $validatedData = $request->validate([
            'order_distance' => 'sometimes|required|integer|unique:dealers,order_distance,' . $dealer->id,
        ]);

        $dealer->update($validatedData);

        return response()->json([
            'message' => 'Dealer berhasil diperbarui',
            'dealer' => $dealer
        ]);
    }
}
