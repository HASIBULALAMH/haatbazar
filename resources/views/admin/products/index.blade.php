@extends('layouts.admin')

@section('title', 'Products')

@section('content')

<div class="topbar">
    <div>
        <h1 class="topbar-title">Products</h1>
        <p class="topbar-subtitle">All products across all shops</p>
    </div>
    <div class="topbar-actions">
        <span style="font-size:13px; color:var(--text-muted);">
            Total: {{ $products->count() }} |
            <span style="color:#86efac;">Active: {{ $products->where('is_active', true)->count() }}</span> |
            <span style="color:#fca5a5;">Inactive: {{ $products->where('is_active', false)->count() }}</span>
        </span>
    </div>
</div>

<div class="card" style="padding:0; overflow:hidden;">
    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>Product</th>
                <th>Shop</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    @if($product->primaryImage)
                        <img src="{{ asset('storage/' . $product->primaryImage->image) }}"
                            style="width:45px; height:45px; object-fit:cover; border-radius:8px; border:1px solid var(--border);">
                    @else
                        <div style="width:45px; height:45px; border-radius:8px; background:rgba(99,102,241,0.1); display:flex; align-items:center; justify-content:center;">
                            <i class="fa fa-image" style="color:#a5b4fc; font-size:16px;"></i>
                        </div>
                    @endif
                </td>
                <td>
                    <div style="font-weight:600;">{{ $product->name }}</div>
                    <div style="font-size:11px; color:var(--text-muted);">{{ Str::limit($product->description, 40) }}</div>
                </td>
                <td>
                    <div style="font-size:13px; color:#a5b4fc;">{{ $product->shop->name }}</div>
                </td>
                <td>
                    <span style="font-size:13px; color:var(--text-muted);">
                        <i class="fa {{ $product->category->icon ?? 'fa-tag' }}"></i>
                        {{ $product->category->name }}
                    </span>
                </td>
                <td>
                    <div style="font-weight:600;">৳{{ number_format($product->price, 2) }}</div>
                    @if($product->discount_price)
                        <div style="font-size:12px; color:#86efac;">৳{{ number_format($product->discount_price, 2) }}</div>
                    @endif
                </td>
                <td>
                    <span style="font-weight:600; color: {{ $product->stock > 10 ? '#86efac' : ($product->stock > 0 ? '#fcd34d' : '#fca5a5') }}">
                        {{ $product->stock }}
                    </span>
                </td>
                <td>
                    @if($product->is_active)
                        <span class="badge" style="background:rgba(22,163,74,0.1); color:#86efac;">Active</span>
                    @else
                        <span class="badge" style="background:rgba(239,68,68,0.1); color:#fca5a5;">Inactive</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">
                    <div class="empty-state">
                        <i class="fa fa-box-open"></i>
                        <p>No products yet.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
