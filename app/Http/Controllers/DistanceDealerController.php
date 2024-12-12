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
            $dealers = Dealer::select(['id', 'kode', 'ahass', 'kota_kab', 'kecamatan', 'status', 'se_area', 'group','order'])->orderBy('order', 'asc');
            return DataTables::of($dealers)
                ->addIndexColumn()
                ->addColumn('order', function ($row) {
                    $value = isset($row->order) ? $row->order : '0';
                    return '<input type="text" class="form-control form-control-sm text-center order-distance" data-id="' . $row->id . '" id="dealer_' . $row->id . '" name="dealer[' . $row->id . ']" value="' . $value . '">';
                })
                ->rawColumns(['order']) // Ensure HTML in the order column is not escaped
                ->make(true);
        }
    }

    public function update(Request $request, $id)
    {
        $dealer = Dealer::findOrFail($id);

        $validatedData = $request->validate([
            'order' => 'sometimes|required|integer|unique:dealers,order,' . $dealer->id,
        ]);

        $dealer->update($validatedData);

        return response()->json([
            'message' => 'Dealer berhasil diperbarui',
            'dealer' => $dealer
        ]);
    }
}
