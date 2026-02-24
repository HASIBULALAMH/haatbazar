<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    public function create()
    {
        // Already has shop → redirect to edit
        if (Auth::user()->shop) {
            return redirect()->route('seller.shop.edit');
        }
        return view('seller.shop.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:shops,name',
            'description' => 'nullable|string|max:500',
            'logo'        => 'nullable|image|max:2048',
            'banner'      => 'nullable|image|max:4096',
        ]);

        $logoPath   = null;
        $bannerPath = null;

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('shops/logos', 'public');
        }
        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store('shops/banners', 'public');
        }

        Shop::create([
            'user_id'     => Auth::id(),
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'logo'        => $logoPath,
            'banner'      => $bannerPath,
            'is_approved' => false,
        ]);

        return redirect()->route('seller.dashboard')
            ->with('success', 'Shop created! Waiting for admin approval.');
    }

    public function edit()
    {
        $shop = Auth::user()->shop;
        if (!$shop) {
            return redirect()->route('seller.shop.create');
        }
        return view('seller.shop.edit', compact('shop'));
    }

    public function update(Request $request)
    {
        $shop = Auth::user()->shop;

        $request->validate([
            'name'        => 'required|string|max:100|unique:shops,name,' . $shop->id,
            'description' => 'nullable|string|max:500',
            'logo'        => 'nullable|image|max:2048',
            'banner'      => 'nullable|image|max:4096',
        ]);

        $logoPath   = $shop->logo;
        $bannerPath = $shop->banner;

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('shops/logos', 'public');
        }
        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store('shops/banners', 'public');
        }

        $shop->update([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'logo'        => $logoPath,
            'banner'      => $bannerPath,
        ]);

        return redirect()->route('seller.shop.edit')
            ->with('success', 'Shop updated successfully!');
    }
}
