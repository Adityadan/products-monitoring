<?php

namespace App\Http\Controllers;

use App\Models\Menus;
use Illuminate\Http\Request;
use Psy\CodeCleaner\FunctionReturnInWriteContextPass;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class MenusController extends Controller
{
    public function index()
    {
        return view('menus.index');
    }
    public function datatable(Request $request)
    {
        // Check if the request is an AJAX request
        if ($request->ajax()) {
            $data = Menus::with('parent')->select(['id', 'name', 'route', 'parent_id', 'icon', 'is_active','order'])->orderBy('order', 'asc');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('parent_name', function ($row) {
                    return $row->parent ? $row->parent->name : '';
                })
                ->editColumn('is_active', function ($row) {
                    return $row->is_active ? 'Active' : 'Inactive';
                })
                ->addColumn('actions', function ($row) {
                    $editBtn = '<button class="btn btn-sm btn-primary edit-menus" data-id="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#menus-modal">Edit</button>';
                    $deleteBtn = '<button class="btn btn-sm btn-danger delete-menus" data-id="' . $row->id . '">Delete</button>';
                    return $editBtn . ' ' . $deleteBtn;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        Menus::create($request->all());
        return response()->json(['message' => 'Menu created successfully']);
    }

    public function edit($id)
    {
        $parent_menu = Menus::where(['parent_id' => null, 'route' => null])->get();

        $menu = Menus::findOrFail($id);
        $permission = Permission::all();
        return response()->json(['menu' => $menu, 'parent_menu'=>$parent_menu, 'permission' => $permission]);
    }

    public function show($id)
    {
        $menu = Menus::findOrFail($id);
        return response()->json($menu);
    }

    public function update(Request $request, $id)
    {
        $menu = Menus::findOrFail($id);
        $menu->update($request->all());
        return response()->json(['message' => 'Menu updated successfully']);
    }

    public function destroy($id)
    {
        $menu = Menus::findOrFail($id);
        $menu->delete();
        return response()->json(['message' => 'Menu deleted successfully']);
    }

    public function parentMenu()
    {
        $parent_menu = Menus::where(['parent_id' => null, 'route' => null])->get();
        $permission = Permission::all();
        return response()->json(['parent_menu' => $parent_menu, 'permission' => $permission]);
    }
}
