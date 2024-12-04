<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PermissionAssignmentController extends Controller
{
    public function index()
    {
        return view('permissions.assign');
    }
}
