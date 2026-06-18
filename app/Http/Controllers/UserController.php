<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function store(Request $request, UserService $userService)
    {
        $this->authorize('create', User::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,manager',
            'location_ids' => 'array',
            'location_ids.*' => 'exists:locations,id',
        ]);

        $userService->createUser(Auth::user(), $validated);

        return redirect()->route('app.users.index');
    }
}
