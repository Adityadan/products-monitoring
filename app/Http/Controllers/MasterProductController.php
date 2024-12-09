<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MasterProductController extends Controller
{
    public function index()
    {
        return view('master-product.index');
    }

    public function datatable(Request $request)
    {
        // Check if the request is an AJAX request
        if ($request->ajax()) {
            $data = Product::select('kode_dealer', 'no_part', 'nama_part', 'nama_gudang', 'oh')
                ->distinct()
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    // $editBtn = '<button class="btn btn-sm btn-primary edit-dealer" data-id="' . $row->no_part . '" data-bs-toggle="modal" data-bs-target="#edit-dealer-modal">Edit</button>';
                    // $deleteBtn = '<button class="btn btn-sm btn-danger delete-dealer" data-id="' . $row->no_part . '">Delete</button>';
                    // return $editBtn . ' ' . $deleteBtn;
                    return '<button class="btn btn-sm btn-primary" data-id="' . $row->no_part . '" data-bs-toggle="modal" data-bs-target="#add-image-modal"><i class="fas fa-image"></i></button>';
                })
                ->rawColumns(['actions']) // Ensure HTML in the actions column is not escaped
                ->make(true);
        }
    }
}
