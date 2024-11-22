<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index');
    }
    public function datatable(Request $request)
    {
        $users = User::select(['id', 'name', 'email', 'username', 'created_at', 'updated_at']);

        return DataTables::of($users)
            ->addIndexColumn()
            ->editColumn('created_at', function ($user) {
                return Carbon::parse($user->created_at)->diffForHumans();
            })
            ->editColumn('updated_at', function ($user) {
                return Carbon::parse($user->updated_at)->diffForHumans();
            })
            ->addColumn('action', function ($user) {
                return '
                <button class="btn btn-sm btn-primary edit-user-button" data-id="' . $user->id . '">Edit</button>
                <button class="btn btn-sm btn-danger delete-user-button" data-id="' . $user->id . '">Delete</button>
            ';
            })
            ->rawColumns(['action']) // Agar tombol dianggap sebagai HTML, bukan teks
            ->make(true);
    }

    // Store a newly created user in storage
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'username' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => bcrypt($request->password),
        ]);

        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }

    // Display the specified user
    public function edit($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json(['data' => $user], 200);
    }

    // Update the specified user in storage
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|nullable|string|max:255',
            'email' => 'sometimes|nullable|string|email|max:255|unique:users,email,' . $id,
            // 'password' => 'sometimes|nullable|string|min:8',
            'username' => 'sometimes|nullable|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $updateFields = $request->only(['name', 'email', /* 'password', */ 'username']);
        // if (isset($updateFields['password'])) {
        //     $updateFields['password'] = bcrypt($updateFields['password']);
        // }

        $user->update($updateFields);

        return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);
    }

    // Remove the specified user from storage
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
