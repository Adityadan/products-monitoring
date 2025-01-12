<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }

    public function datatable_target(Request $request)
    {
        $data = DB::table('summary_rod')
            ->leftJoin('target', 'summary_rod.kode_customer', '=', 'target.kode_customer')
            ->whereNull('summary_rod.deleted_at')
            ->whereNull('target.deleted_at')
            ->select(
                'summary_rod.*',
                'target.target_app',
                'target.target_part',
                'target.target_oli'
            )
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('periode', function ($row) {
                return Carbon::parse($row->periode)->translatedFormat('F Y');
            })
            ->make(true);
    }

    public function chartTarget(Request $request)
    {
        $data = DB::table('summary_rod')
            ->rightJoin('target', 'summary_rod.kode_customer', '=', 'target.kode_customer')
            ->whereNull('summary_rod.deleted_at')
            ->whereNull('target.deleted_at')
            ->select(
                'target.customer_name',
                'summary_rod.periode',
                'summary_rod.total_amount_app',
                'summary_rod.total_amount_part',
                'summary_rod.total_amount_oil',
                'target.target_app',
                'target.target_part',
                'target.target_oli'
            )
            ->get();

        $result = [];

        foreach ($data as $item) {
            $result[] = [
                'periode' => Carbon::parse($item->periode)->translatedFormat('F Y'),
                'customer_name' => $item->customer_name,
                'pendapatan' => [
                    'app' => $item->total_amount_app ?? 0,
                    'part' => $item->total_amount_part ?? 0,
                    'oli' => $item->total_amount_oil ?? 0,
                ],
                'target' => [
                    'app' => $item->target_app ?? 0,
                    'part' => $item->target_part ?? 0,
                    'oli' => $item->target_oli ?? 0,
                ],
            ];
        }
        return response()->json($result);
    }

    public function chartSales(Request $request)
    {
        $sixMonthsAgo = Carbon::now()->subMonths(6);

        $data = Sales::select('periode', DB::raw('SUM(qty) as total_quantity'))
            ->whereNull('deleted_at')
            ->where('periode', '>=', $sixMonthsAgo)
            ->groupBy('periode')
            ->get()
            ->toArray();

        return response()->json($data);
    }
}
