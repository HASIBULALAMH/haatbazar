<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $shop = Auth::user()->shop;

        if (!$shop) {
            return redirect()->route('seller.shop.create')
                ->with('error', 'Please create your shop first!');
        }

         $products = $shop->products()->with(['category', 'primaryImage', 'images'])->latest()->get();

        return view('seller.products.index', compact('products'));
    }

    public function create()
    {
        $shop = Auth::user()->shop;

        if (!$shop) {
            return redirect()->route('seller.shop.create')
                ->with('error', 'Please create your shop first!');
        }

        $categories = Category::latest()->get();
        return view('seller.products.create', compact('categories'));
    }

   public function store(Request $request)
{
    $request->validate([
        'name'           => 'required|string|max:200',
        'category_id'    => 'required|exists:categories,id',
        'description'    => 'nullable|string',
        'price'          => 'required|numeric|min:0',
        'discount_price' => 'nullable|numeric|min:0|lt:price',
        'stock'          => 'required|integer|min:0',
        'is_active'      => 'boolean',
        'images'         => 'nullable|array',
        'images.*'       => 'image|max:2048',
    ]);

    $shop = Auth::user()->shop;

    $product = Product::create([
        'shop_id'        => $shop->id,
        'category_id'    => $request->category_id,
        'name'           => $request->name,
        'slug'           => Str::slug($request->name) . '-' . uniqid(),
        'description'    => $request->description,
        'price'          => $request->price,
        'discount_price' => $request->discount_price,
        'stock'          => $request->stock,
        'is_active'      => $request->boolean('is_active', true),
    ]);

    // Images upload
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $index => $image) {
            $path = $image->store('products', 'public');
            ProductImage::create([
                'product_id' => $product->id,
                'image'      => $path,
                'is_primary' => $index === 0, // first image = primary
            ]);
        }
    }

    return redirect()->route('seller.products.index')
        ->with('success', 'Product added successfully!');
    }


    public function edit(Product $product)
    {
        // Only own products
        if ($product->shop_id !== Auth::user()->shop->id) {
            abort(403);
        }

        $categories = Category::latest()->get();
        return view('seller.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
    if ($product->shop_id !== Auth::user()->shop->id) {
        abort(403);
    }

    $request->validate([
        'name'           => 'required|string|max:200',
        'category_id'    => 'required|exists:categories,id',
        'description'    => 'nullable|string',
        'price'          => 'required|numeric|min:0',
        'discount_price' => 'nullable|numeric|min:0|lt:price',
        'stock'          => 'required|integer|min:0',
        'images'         => 'nullable|array',
        'images.*'       => 'image|max:2048',
    ]);

    $product->update([
        'category_id'    => $request->category_id,
        'name'           => $request->name,
        'slug'           => Str::slug($request->name) . '-' . uniqid(),
        'description'    => $request->description,
        'price'          => $request->price,
        'discount_price' => $request->discount_price,
        'stock'          => $request->stock,
        'is_active'      => $request->boolean('is_active'),
    ]);

    // New images upload
    if ($request->hasFile('images')) {
        // First new image = primary, remove old primary
        $product->images()->update(['is_primary' => false]);

        foreach ($request->file('images') as $index => $image) {
            $path = $image->store('products', 'public');
            ProductImage::create([
                'product_id' => $product->id,
                'image'      => $path,
                'is_primary' => $index === 0,
            ]);
        }
    }

    return redirect()->route('seller.products.index')
        ->with('success', 'Product updated successfully!');
    }

   public function destroy(Product $product)
    {
    if ($product->shop_id !== Auth::user()->shop->id) {
        abort(403);
    }

    // Delete images from storage
    foreach ($product->images as $image) {
        Storage::disk('public')->delete($image->image);
    }

    $product->delete();

    return redirect()->route('seller.products.index')
        ->with('success', 'Product deleted successfully!');
    }

    public function destroyImage(ProductImage $image)
{
    // Only own product's image
    if ($image->product->shop_id !== Auth::user()->shop->id) {
        abort(403);
    }

    Storage::disk('public')->delete($image->image);

    // If deleted image was primary, make first remaining image primary
    $wasPrimary = $image->is_primary;
    $image->delete();

    if ($wasPrimary) {
        $image->product->images()->first()?->update(['is_primary' => true]);
    }

    return back()->with('success', 'Image removed!');
}
}
