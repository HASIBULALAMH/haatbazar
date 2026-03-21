<?php

namespace App\Http\Controllers\Seller\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // Show registration form
    public function create()
    {
        return view('auth.seller.register');
    }

    // Handle registration
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'required|string|max:20',
            'password' => 'required|min:8|confirmed',
        ]);

        // Create seller user
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role'     => 'seller',
        ]);

        // Login seller user
        Auth::guard('seller')->login($user);
        return redirect()->route('seller.dashboard');
    }
}
