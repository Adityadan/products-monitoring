<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        return view('permissions.index');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:permissions,name']);
        Permission::create(['name' => $request->name]);

        return redirect()->back()->with('success', 'Permission berhasil dibuat!');
    }

    public function edit($id)
    {
        $permission = Permission::findOrFail($id);

        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|unique:permissions,name,' . $id]);
        $permission = Permission::findOrFail($id);
        $permission->update(['name' => $request->name]);

        return redirect()->route('permissions.index')->with('success', 'Permission berhasil diupdate!');
    }

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return redirect()->route('permissions.index')->with('success', 'Permission berhasil dihapus!');
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
