<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'buyer')
            ->latest()
            ->get();
        return view('admin.users.index', compact('users'));
    }

    public function toggle(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', "User '{$user->name}' status updated!");
    }

    public function sellers()
    {
        $sellers = User::where('role', 'seller')
            ->with('shop')
            ->latest()
            ->get();
        return view('admin.sellers.index', compact('sellers'));
    }

    public function sellerToggle(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', "Seller '{$user->name}' status updated!");
    }
}
