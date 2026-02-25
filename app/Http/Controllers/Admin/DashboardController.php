<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Shop;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'    => User::where('role', 'buyer')->count(),
            'total_sellers'  => User::where('role', 'seller')->count(),
            'total_shops'    => Shop::count(),
            'pending_shops'  => Shop::where('is_approved', false)->count(),
            'total_products' => Product::count(),
            'active_products'=> Product::where('is_active', true)->count(),
        ];

        $recentSellers = User::where('role', 'seller')
            ->with('shop')
            ->latest()
            ->take(5)
            ->get();

        $recentShops = Shop::with('user')
            ->where('is_approved', false)
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentSellers', 'recentShops'));
    }
}
