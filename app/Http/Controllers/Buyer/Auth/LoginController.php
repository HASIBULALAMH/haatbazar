<?php

namespace App\Http\Controllers\Buyer\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    // Show login form
    public function create()
    {
        return view('auth.buyer.login');
    }

    // Handle login
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);
    // Attempt login with buyer guard and role check
        if (Auth::guard('buyer')->attempt([
            'email'    => $request->email,
            'password' => $request->password,
            'role'     => 'buyer',
        ], $request->remember))
        {
        $request->session()->regenerate();
            return redirect()->route('buyer.dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials or not a buyer account.',
        ])->onlyInput('email');
    }

    // Handle logout
    public function destroy(Request $request)
    {
        Auth::guard('buyer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('buyer.login');

    }
}
