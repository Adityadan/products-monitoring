<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleAssignmentController extends Controller
{
    public function index()
    {
        $users = User::select('id', 'name')->get();
        $roles = Role::select('id', 'name')->get();
        // dd($users->toArray(),$roles->toArray());
        return view('roles.assign', compact('users', 'roles'));
    }

    public function assign(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::findOrFail($request->user_id);

        // Assign the role to the user
        $user->assignRole($request->role);

        return response()->json(['message' => 'Role assigned successfully']);
    }


    public function removeRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::findOrFail($request->user_id);

        // Remove the role from the user
        $user->removeRole($request->role);

        return response()->json(['message' => 'Role removed successfully']);
    }


    public function editAssignedRoles($userId)
    {
        $user = User::with('roles')->findOrFail($userId);
        $allRoles = Role::pluck('name'); // Fetch all roles

        return response()->json([
            'user' => $user,
            'assignedRoles' => $user->roles->pluck('name'),
            'allRoles' => $allRoles,
        ]);
    }
}
