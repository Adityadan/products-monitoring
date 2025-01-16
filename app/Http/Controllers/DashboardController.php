<?php

namespace App\Http\Controllers;

use App\Models\Dealer;
use App\Models\Sales;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    protected $kode_customer;

    public function __construct()
    {
        $user = auth()->user();
        if ($user && $user->kode_dealer) {
            $this->kode_customer = Dealer::where('kode', $user->kode_dealer)->first()->kode_customer;
        } else {
            $this->kode_customer = null;
        }
    }
    public function index()
    {
        return view('dashboard.index');
    }

    public function datatable_target(Request $request)
    {
        $query = DB::table('summary_rod')
            ->rightJoin('target', 'summary_rod.kode_customer', '=', 'target.kode_customer')
            ->whereNull('summary_rod.deleted_at')
            ->whereNull('target.deleted_at')
            ->select(
                'summary_rod.*',
                'target.target_app',
                'target.target_part',
                'target.target_oli'
            );

        // Filter berdasarkan nama dealer jika bukan main_dealer
        if (!auth()->user()->hasRole('main_dealer') && !auth()->user()->hasRole('superadmin')) {
            $query->where('summary_rod.kode_customer', $this->kode_customer);
        }

        // Ambil data
        $data = $query->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('periode', function ($row) {
                return Carbon::parse($row->periode)->translatedFormat('F Y');
            })
            ->make(true);
    }


    public function chartTarget(Request $request)
    {

        $query = DB::table('summary_rod')
            ->rightJoin('target', 'summary_rod.kode_customer', '=', 'target.kode_customer')
            ->leftJoin('dealers', 'summary_rod.kode_customer', '=', 'dealers.kode')
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
            );

        // Filter berdasarkan nama dealer jika bukan main_dealer
        if (!auth()->user()->hasRole('main_dealer') && !auth()->user()->hasRole('superadmin')) {
            $query->where('summary_rod.kode_customer', $this->kode_customer);
        }

        // Ambil data
        $data = $query->get();

        // Transformasi data
        $result = $data->map(function ($item) {
            return [
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
        });

        // Kembalikan respons dalam JSON
        return response()->json($result);
    }


    public function chartSales()
    {
        $sixMonthsAgo = Carbon::now()->subMonths(6);
        $now = Carbon::now();

        $query = Sales::select('periode', DB::raw('SUM(qty) as total_quantity'))
            ->whereNull('deleted_at')
            ->whereBetween('periode', [$sixMonthsAgo, $now])
            ->groupBy('periode')
            ->orderBy('periode');

        if (!auth()->user()->hasRole('main_dealer') && !auth()->user()->hasRole('superadmin')) {
            $query->where('kode_dealer', auth()->user()->kode_dealer);
        }

        $data = $query->get()->toArray();
        return response()->json($data);
    }
}
