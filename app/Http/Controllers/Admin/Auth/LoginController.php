<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Show login form
    public function create()
    {
        return view('auth.admin.login');
    }

    // Handle login
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Attempt login with admin guard and role check
        if (Auth::guard('admin')->attempt([
            'email'    => $request->email,
            'password' => $request->password,
            'role'     => 'admin',
        ], $request->remember)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials or not an admin account.',
        ])->onlyInput('email');
    }

    // Handle logout
    public function destroy(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
