<?php

namespace App\Http\Controllers;

use App\Models\LogImport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            ->select('log_imports.file_name','log_imports.file_type', 'log_imports.status', 'log_imports.created_at', 'users.name as created_by' ,'users.*');
            if (!auth()->user()->hasRole('main_dealer')) {
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
}
