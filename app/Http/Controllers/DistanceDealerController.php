<?php

namespace App\Http\Controllers;

use App\Models\Dealer;
use App\Models\DistanceOrderDealer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DistanceDealerController extends Controller
{
    public function index()
    {
        $dealers_area = Dealer::select('kota_kab')->distinct()->get();
        $dealer_id = Auth::user()->id;
        return view('distance-dealer.index', compact('dealers_area', 'dealer_id'));
    }

    public function datatable(Request $request)
    {
        // Check if the request is an AJAX request
        if ($request->ajax()) {
            $dealers = Dealer::select(['id', 'kode', 'ahass', 'kota_kab', 'kecamatan', 'status', 'se_area', 'group', 'order_distance'])->orderBy('order_distance', 'asc');
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

    public function saveArea(Request $request)
    {
        // Validasi input
        $request->validate([
            'dealer_areas' => 'required|array',
        ]);
        $areas = $request->dealer_areas;
        $dealer_id = Auth::user()->id;
        $kode_dealer = Auth::user()->kode_dealer;
        // Siapkan data untuk disimpan
        $data = array_map(function ($area, $index) use ($dealer_id,$kode_dealer) {
            return [
                'dealer_id' => $dealer_id,
                'kode_dealer' => $kode_dealer,
                'area' => $area,
                'order_distance' => $index + 1, // Urutan dimulai dari 1
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $areas, array_keys($areas));

        // Gunakan transaksi untuk memastikan konsistensi data
        DB::transaction(function () use ($dealer_id, $data) {
            // Hapus data lama dealer dari tabel distance_order_dealer
            DistanceOrderDealer::where('dealer_id', $dealer_id)->where('kode_dealer', $data[0]['kode_dealer'])->where('area', '!=', $data[0]['area'])->whereNotNull('area')->delete();

            // Simpan data baru ke database
            DistanceOrderDealer::insert($data);
        });

        // Response sukses
        return response()->json(['message' => 'Area order berhasil disimpan'], 200);
    }
}
