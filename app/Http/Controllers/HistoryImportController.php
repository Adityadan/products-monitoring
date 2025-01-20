<?php

namespace App\Http\Controllers;

use App\Exports\LogImportsExport;
use App\Models\LogImport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class HistoryImportController extends Controller
{
    public function index()
    {
        return view('history-import.index');
    }

    public function datatable()
    {
        $data = DB::table('log_imports')
            ->leftJoin('users', DB::raw('CAST(log_imports.created_by AS BIGINT)'), '=', 'users.id')
            ->select('log_imports.file_name','log_imports.file_type', 'log_imports.status', 'log_imports.created_at', 'users.name as created_by' ,'users.*')
            ->where('log_imports.deleted_at', null);
            if (!auth()->user()->hasRole('main_dealer') && !auth()->user()->hasRole('superadmin')) {
                $data->where('log_imports.created_by', auth()->user()->id);
            }
            $data->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->editColumn('created_at', function ($data) {
                return Carbon::parse($data->created_at)->translatedFormat('d F Y H:i:s');
            })
            ->make(true);
    }
    public function exportExcel()
    {
        try {
            $data = DB::table('log_imports')
            ->leftJoin('users', DB::raw('CAST(log_imports.created_by AS BIGINT)'), '=', 'users.id')
            ->select('log_imports.file_name','log_imports.file_type', 'log_imports.status', 'log_imports.created_at', 'users.name as created_by' ,'users.*')
            ->where('log_imports.deleted_at', null);
            if (!Auth::user()->hasRole('main_dealer') && !Auth::user()->hasRole('superadmin')) {
                $data->where('log_imports.created_by', Auth::user()->id);
            }
            $data = $data->get();
            $exportData = $data->map(function ($order, $index) {
                return [
                    'No' => $index + 1,
                    'File Name' => $order->file_name,
                    'File Type' => $order->file_type,
                    'Status' => $order->status,
                    'Uploaded By' => $order->created_by,
                    'Uploaded At' => Carbon::parse($order->created_at)->format('Y-m-d H:i:s'),
                ];
            });

            $filename = 'log_imports_' . now()->format('Ymd_His') . '.xlsx';

            return Excel::download(new LogImportsExport($exportData), $filename);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Export failed: ' . $e->getMessage()
            ], 500);        }

    }
}
