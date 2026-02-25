<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $shop = $user->shop;

        $stats = [
            'total_products' => 0,
            'total_orders'   => 0,
            'total_earnings' => 0,
            'avg_rating'     => 0,
        ];

        if ($shop) {
            $stats['total_products'] = $shop->products()->count();
            // Orders & earnings will be added after order system
        }

        $recentProducts = $shop
            ? $shop->products()->with('primaryImage', 'category')->latest()->take(5)->get()
            : collect();

        return view('seller.dashboard', compact('stats', 'recentProducts'));
    }
}
