<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): \Illuminate\View\View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'organization_name' => 'required|string|max:255',
        ]);

        $organization = Organization::create([
            'name' => $request->organization_name,
            'slug' => Str::slug($request->organization_name) . '-' . Str::random(5),
            'is_onboarded' => false,
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'organization_id' => $organization->id,
            'role' => 'admin',
        ]);

        event(new Registered($user));

        Auth::login($user);

        if ($request->has('plan_id')) {
            session(['selected_plan_id' => $request->plan_id]);
        }
        if ($request->has('cycle')) {
            session(['selected_plan_cycle' => $request->cycle]);
        }

        return redirect()->route('app.onboarding');
    }
}
