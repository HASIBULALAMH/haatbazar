@extends('layouts.seller')

@section('title', 'My Products')

@section('content')

<div class="topbar">
    <div>
        <h1 class="topbar-title">My Products</h1>
        <p class="topbar-subtitle">Manage your product listings</p>
    </div>
    <div class="topbar-actions">
        <a href="{{ route('seller.products.create') }}" class="btn-submit"
            style="background:linear-gradient(135deg,#d97706,#b45309); width:auto; padding:10px 20px; margin:0; font-size:13px; display:inline-flex; align-items:center; gap:8px;">
            <i class="fa fa-plus"></i> Add Product
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert-success" style="margin-bottom:20px;">
        <i class="fa fa-circle-check"></i> {{ session('success') }}
    </div>
@endif

<div class="card" style="padding:0; overflow:hidden;">
    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    @if($product->primaryImage)
                        <img src="{{ asset('storage/' . $product->primaryImage->image) }}"
                            alt="{{ $product->name }}"
                            style="width:45px; height:45px; object-fit:cover; border-radius:8px; border:1px solid var(--border);">
                    @else
                        <div style="width:45px; height:45px; border-radius:8px; background:rgba(217,119,6,0.1); display:flex; align-items:center; justify-content:center;">
                            <i class="fa fa-image" style="color:#fcd34d; font-size:16px;"></i>
                        </div>
                    @endif
                </td>
                <td>
                    <div style="font-weight:600;">{{ $product->name }}</div>
                    <div style="font-size:11px; color:var(--text-muted);">{{ $product->images->count() }} image(s)</div>
                </td>
                <td>
                    <span style="font-size:13px; color:#fcd34d;">
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
                <td>
                    <div style="display:flex; gap:8px;">
                        <a href="{{ route('seller.products.edit', $product) }}"
                           style="padding:6px 12px; background:rgba(217,119,6,0.1); color:#fcd34d; border-radius:8px; font-size:12px; text-decoration:none;">
                            <i class="fa fa-pen"></i> Edit
                        </a>
                        <form method="POST" action="{{ route('seller.products.destroy', $product) }}"
                              onsubmit="return confirm('Delete this product?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                style="padding:6px 12px; background:rgba(239,68,68,0.1); color:#fca5a5; border-radius:8px; font-size:12px; border:none; cursor:pointer;">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">
                    <div class="empty-state">
                        <i class="fa fa-box-open"></i>
                        <p>No products yet.</p>
                        <a href="{{ route('seller.products.create') }}"
                           style="color:#fcd34d; font-size:13px; margin-top:8px; display:inline-block;">
                            Add Your First Product →
                        </a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
