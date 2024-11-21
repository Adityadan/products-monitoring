<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionController extends Controller
{
    // Menampilkan semua roles, permissions, dan users
    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $users = User::with('roles', 'permissions')->get();

        return view('role-permission.index', compact('roles', 'permissions', 'users'));
    }

    // Membuat role baru
    public function storeRole(Request $request)
    {
        $request->validate(['name' => 'required|unique:roles,name']);
        Role::create(['name' => $request->name]);

        return redirect()->back()->with('success', 'Role berhasil dibuat!');
    }

    // Membuat permission baru
    public function storePermission(Request $request)
    {
        $request->validate(['name' => 'required|unique:permissions,name']);
        Permission::create(['name' => $request->name]);

        return redirect()->back()->with('success', 'Permission berhasil dibuat!');
    }

    // Memberikan role ke user
    public function assignRoleToUser(Request $request, User $user)
    {
        $request->validate(['role' => 'required|exists:roles,name']);
        $user->assignRole($request->role);

        return redirect()->back()->with('success', 'Role berhasil diberikan ke user!');
    }

    // Memberikan permission ke user
    public function assignPermissionToUser(Request $request, User $user)
    {
        $request->validate(['permission' => 'required|exists:permissions,name']);
        $user->givePermissionTo($request->permission);

        return redirect()->back()->with('success', 'Permission berhasil diberikan ke user!');
    }

    // Menghapus role dari user
    public function removeRoleFromUser(Request $request, User $user)
    {
        $request->validate(['role' => 'required|exists:roles,name']);
        $user->removeRole($request->role);

        return redirect()->back()->with('success', 'Role berhasil dihapus dari user!');
    }

    // Menghapus permission dari user
    public function removePermissionFromUser(Request $request, User $user)
    {
        $request->validate(['permission' => 'required|exists:permissions,name']);
        $user->revokePermissionTo($request->permission);

        return redirect()->back()->with('success', 'Permission berhasil dihapus dari user!');
    }
}
