<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;

class ShopController extends Controller
{
    public function index()
    {
        $shops = Shop::with('user')->latest()->get();
        return view('admin.shops.index', compact('shops'));
    }

    public function approve(Shop $shop)
    {
        $shop->update(['is_approved' => true]);

        return back()->with('success', "'{$shop->name}' has been approved!");
    }

    public function reject(Shop $shop)
    {
        $shop->update(['is_approved' => false]);

        return back()->with('success', "'{$shop->name}' has been rejected!");
    }
}
