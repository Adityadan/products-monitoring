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
            $dealers = Role::select(['id', 'name', 'guard_name' ]);

            return DataTables::of($dealers)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $editBtn = '<button class="btn btn-sm btn-primary edit-dealer" data-id="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#edit-dealer-modal">Edit</button>';
                    $deleteBtn = '<button class="btn btn-sm btn-danger delete-dealer" data-id="' . $row->id . '">Delete</button>';
                    return $editBtn . ' ' . $deleteBtn;
                })
                ->rawColumns(['actions']) // Ensure HTML in the actions column is not escaped
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:roles,name']);
        Role::create(['name' => $request->name]);

        return redirect()->back()->with('success', 'Role berhasil dibuat!');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);

        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|unique:roles,name,' . $id]);
        $role = Role::findOrFail($id);
        $role->update(['name' => $request->name]);

        return redirect()->route('roles.index')->with('success', 'Role berhasil diupdate!');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus!');
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
