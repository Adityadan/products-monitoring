<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RolesController extends Controller
{
    public function index()
    {
        return view('roles.index');
    }

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            $roles = Role::select(['id', 'name', 'guard_name']);

            return DataTables::of($roles)
                ->addIndexColumn() // Automatically add row numbering
                ->addColumn('actions', function ($role) {
                    $editBtn = '<button class="btn btn-sm btn-primary edit-roles" data-id="' . $role->id . '" data-bs-toggle="modal" data-bs-target="#role-modal">Edit</button>';
                    $deleteBtn = '<button class="btn btn-sm btn-danger delete-roles" data-id="' . $role->id . '">Delete</button>';
                    return $editBtn . ' ' . $deleteBtn;
                })
                ->rawColumns(['actions']) // Prevent escaping of action buttons' HTML
                ->make(true);
        }
    }


    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:roles,name']);
        $role = Role::create(['name' => $request->name]);

        return response()->json(['message' => 'Role successfully created!', 'role' => $role], 201);
    }


    public function edit($id)
    {
        $role = Role::findOrFail($id);

        return response()->json(['role' => $role], 200);
    }


    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|unique:roles,name,' . $id]);
        $role = Role::findOrFail($id);
        $role->update(['name' => $request->name]);

        return response()->json(['message' => 'Role successfully updated!', 'role' => $role], 200);
    }


    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json(['message' => 'Role successfully deleted!'], 200);
    }


    public function assignRoleToUser(Request $request, User $user)
    {
        $request->validate(['role' => 'required|exists:roles,name']);
        $user->assignRole($request->role);

        return redirect()->back()->with('success', 'Role berhasil diberikan ke user!');
    }

    public function removeRoleFromUser(Request $request, User $user)
    {
        $request->validate(['role' => 'required|exists:roles,name']);
        $user->removeRole($request->role);

        return redirect()->back()->with('success', 'Role berhasil dihapus dari user!');
    }
}
