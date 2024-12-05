<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
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
                    return
                        '
                    <button class="btn btn-sm btn-warning assign-permission-button" data-id="' . $role->id . '"><i class="fas fa-user-cog"></i></button>
                    <button class="btn btn-sm btn-primary edit-roles" data-id="' . $role->id . '" data-bs-toggle="modal" data-bs-target="#role-modal">Edit</button>
                    <button class="btn btn-sm btn-danger delete-roles" data-id="' . $role->id . '">Delete</button>
                    ';
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
    public function assignPermission(Request $request)
    {
        // Debugging Input
        // dd($request->all());

        // Validasi request
        $validatedData = $request->validate([
            'roles_id' => 'required|exists:roles,id', // Pastikan role ID ada di tabel roles
            'permissions' => 'required|array',
            'permissions.*' => 'nullable|string|exists:permissions,name', // Pastikan permission valid
        ]);

        // Hapus nilai null dari permissions
        $permissions = array_filter($validatedData['permissions']);

        if (empty($permissions)) {
            return response()->json(['message' => 'No valid permissions provided'], 400);
        }

        // Temukan role berdasarkan roles_id
        $role = Role::findOrFail($validatedData['roles_id']);

        // Sinkronisasi permissions dengan role
        $role->syncPermissions($permissions);

        return response()->json([
            'message' => 'Permissions assigned successfully',
            'permissions' => $permissions
        ], 200);
    }


    public function editAssignedPermission($rolesId)
    {
        $roles = Role::with('permissions')->findOrFail($rolesId);
        $allPermissions = Permission::pluck('name'); // Fetch all roles
        return response()->json([
            'roles' => $roles,
            'assignedPermissions' => $roles->permissions->pluck('name'),
            'allPermissions' => $allPermissions,
        ]);
    }
}
