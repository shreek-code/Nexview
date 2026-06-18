<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class PlatformUserController extends Controller
{
    public function index()
    {
        return view('admin.platform-users.index');
    }

    public function create()
    {
        return view('admin.platform-users.create');
    }

    public function edit(\App\Models\PlatformUser $user)
    {
        return view('admin.platform-users.edit', compact('user'));
    }
}
