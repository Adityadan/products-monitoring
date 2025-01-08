<?php

namespace App\Http\Controllers;

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
            ->editColumn('total_amount_app', function ($row) {
                $percentage = $row->target_app ? round($row->total_amount_app / $row->target_app * 100) : 0;
                $color = $percentage < 100 ? 'red' : 'green';
                return 'Rp' . number_format($row->total_amount_app, 0, ',', '.') . " (<span style='color: {$color};'>{$percentage}%</span>)";
            })
            ->editColumn('target_app', function ($row) {
                return 'Rp' . number_format($row->target_app, 0, ',', '.');
            })
            ->editColumn('total_amount_part', function ($row) {
                $percentage = $row->target_part ? round($row->total_amount_part / $row->target_part * 100) : 0;
                $color = $percentage < 100 ? 'red' : 'green';
                return 'Rp' . number_format($row->total_amount_part, 0, ',', '.') . " (<span style='color: {$color};'>{$percentage}%</span>)";
            })
            ->editColumn('target_part', function ($row) {
                return 'Rp' . number_format($row->target_part, 0, ',', '.');
            })
            ->editColumn('total_amount_oil', function ($row) {
                $percentage = $row->target_oli ? round($row->total_amount_oil / $row->target_oli * 100) : 0;
                $color = $percentage < 100 ? 'red' : 'green';
                return 'Rp' . number_format($row->total_amount_oil, 0, ',', '.') . " (<span style='color: {$color};'>{$percentage}%</span>)";
            })
            ->editColumn('target_oli', function ($row) {
                return 'Rp' . number_format($row->target_oli, 0, ',', '.');
            })
            ->editColumn('periode', function ($row) {
                return Carbon::parse($row->periode)->translatedFormat('F Y');
            })
            ->rawColumns(['total_amount_app', 'total_amount_part', 'total_amount_oil'])
            ->make(true);
    }
}
