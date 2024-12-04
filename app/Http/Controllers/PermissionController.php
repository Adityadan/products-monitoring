<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public function index()
    {
        return view('permissions.index');
    }

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            $data = Permission::select(['id', 'name', 'guard_name']);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $editBtn = '<button class="btn btn-sm btn-primary edit-permission-button" data-id="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#permission-modal">Edit</button>';
                    $deleteBtn = '<button class="btn btn-sm btn-danger delete-permission-button" data-id="' . $row->id . '">Delete</button>';
                    return $editBtn . ' ' . $deleteBtn;
                })
                ->rawColumns(['actions']) // Ensure HTML in the actions column is not escaped
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:permissions,name']);
        $permission = Permission::create(['name' => $request->name]);

        return response()->json(['message' => 'Permission successfully created!', 'permission' => $permission], 201);
    }


    public function edit($id)
    {
        $permission = Permission::findOrFail($id);

        return response()->json(['permission' => $permission], 200);
    }


    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|unique:permissions,name,' . $id]);
        $permission = Permission::findOrFail($id);
        $permission->update(['name' => $request->name]);

        return response()->json(['message' => 'Permission successfully updated!', 'permission' => $permission], 200);
    }


    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return response()->json(['message' => 'Permission successfully deleted!'], 200);
    }

    public function assignPermissionToUser(Request $request, User $user)
    {
        $request->validate(['permission' => 'required|exists:permissions,name']);
        $user->givePermissionTo($request->permission);

        return redirect()->back()->with('success', 'Permission berhasil diberikan ke user!');
    }

    public function removePermissionFromUser(Request $request, User $user)
    {
        $request->validate(['permission' => 'required|exists:permissions,name']);
        $user->revokePermissionTo($request->permission);

        return redirect()->back()->with('success', 'Permission berhasil dihapus dari user!');
    }
}
