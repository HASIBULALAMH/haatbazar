<?php

namespace App\Http\Controllers\Seller\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Show login form
    public function create()
    {
        return view('auth.seller.login');
    }

    // Handle login
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Attempt login with seller guard and role check
        if (Auth::guard('seller')->attempt([
            'email'    => $request->email,
            'password' => $request->password,
            'role'     => 'seller',
        ], $request->remember)) {
            $request->session()->regenerate();
            return redirect()->route('seller.dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials or not a seller account.',
        ])->onlyInput('email');
    }

    // Handle logout
    public function destroy(Request $request)
    {
        Auth::guard('seller')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('seller.login');
    }
}
