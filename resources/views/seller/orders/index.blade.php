@extends('layouts.seller')

@section('title', 'My Orders')

@section('content')

<div class="topbar">
    <div>
        <h1 class="topbar-title">Orders</h1>
        <p class="topbar-subtitle">{{ $orders->total() }} order(s) received</p>
    </div>
</div>

{{-- Filter tabs --}}
<div style="display:flex; gap:8px; margin-bottom:20px; flex-wrap:wrap;">
    @foreach(['all','pending','processing','shipped','delivered','cancelled'] as $tab)
    <a href="{{ route('seller.orders.index', ['status' => $tab]) }}"
       style="padding:7px 16px; border-radius:20px; font-size:12px; font-weight:600; text-decoration:none; transition:all 0.2s;
           {{ request('status', 'all') === $tab
               ? 'background:rgba(99,102,241,0.2); color:#a5b4fc; border:1px solid rgba(99,102,241,0.3);'
               : 'background:rgba(255,255,255,0.04); color:var(--text-muted); border:1px solid var(--border);' }}">
        {{ ucfirst($tab) }}
    </a>
    @endforeach
</div>

@if($orders->count() > 0)
<div style="display:flex; flex-direction:column; gap:14px;">
    @foreach($orders as $order)
    <div class="card" style="padding:20px;">

        {{-- Header --}}
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; flex-wrap:wrap; gap:8px;">
            <div>
                <div style="font-size:14px; font-weight:700;">Order #{{ $order->id }}</div>
                <div style="font-size:12px; color:var(--text-muted);">
                    {{ $order->created_at->format('d M Y, h:i A') }} ·
                    <i class="fa fa-user"></i> {{ $order->buyer->name }}
                </div>
            </div>
            <div style="display:flex; align-items:center; gap:8px;">
                <span style="font-size:11px; font-weight:700; padding:4px 12px; border-radius:20px; text-transform:uppercase;
                    background:{{ $order->statusColor() }}20; color:{{ $order->statusColor() }};">
                    {{ $order->status }}
                </span>
                <span style="font-size:11px; padding:4px 12px; border-radius:20px; text-transform:uppercase;
                    background:{{ $order->paymentColor() }}20; color:{{ $order->paymentColor() }};">
                    {{ $order->payment_method }}
                </span>
            </div>
        </div>

        {{-- Seller's items only --}}
        <div style="display:flex; flex-direction:column; gap:8px; margin-bottom:14px;">
            @foreach($order->sellerItems as $item)
            <div style="display:flex; align-items:center; gap:12px; padding:10px; background:rgba(255,255,255,0.02); border-radius:8px; border:1px solid var(--border);">
                <div style="flex:1; font-size:13px; font-weight:600;">{{ $item->product_name }}</div>
                <div style="font-size:12px; color:var(--text-muted);">{{ $item->quantity }}×</div>
                <div style="font-size:13px; font-weight:700; color:#a5b4fc;">৳{{ number_format($item->subtotal, 0) }}</div>
            </div>
            @endforeach
        </div>

        {{-- Footer --}}
        <div style="display:flex; align-items:center; justify-content:space-between; border-top:1px solid var(--border); padding-top:12px;">
            <div style="font-size:14px; font-weight:700; color:#a5b4fc;">
                ৳{{ number_format($order->sellerItems->sum('subtotal'), 0) }}
            </div>
            <a href="{{ route('seller.orders.show', $order) }}"
               style="padding:8px 16px; background:rgba(99,102,241,0.1); color:#a5b4fc; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none; border:1px solid rgba(99,102,241,0.2);">
                <i class="fa fa-eye"></i> Manage Order
            </a>
        </div>

    </div>
    @endforeach
</div>

{{-- Pagination --}}
<div style="margin-top:20px;">{{ $orders->links() }}</div>

@else
<div class="card" style="padding:60px; text-align:center;">
    <i class="fa fa-box-open" style="font-size:48px; color:var(--text-muted); opacity:0.3; display:block; margin-bottom:16px;"></i>
    <h3 style="font-size:18px; font-weight:600; margin-bottom:8px;">No orders yet</h3>
    <p style="color:var(--text-muted);">Orders will appear here when buyers purchase your products.</p>
</div>
@endif

@endsection
