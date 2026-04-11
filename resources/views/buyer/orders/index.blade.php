@extends('layouts.buyer')

@section('title', 'My Orders')

@section('content')

<div class="topbar">
    <div>
        <h1 class="topbar-title">My Orders</h1>
        <p class="topbar-subtitle">{{ $orders->total() }} order(s) found</p>
    </div>
    <a href="{{ route('products.index') }}" class="btn-submit" style="padding:10px 20px; font-size:13px;">
        <i class="fa fa-bag-shopping"></i> Continue Shopping
    </a>
</div>

@if($orders->count() > 0)
    <div style="display:flex; flex-direction:column; gap:14px;">
        @foreach($orders as $order)
        <div class="card" style="padding:20px;">

            {{-- Order Header --}}
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; flex-wrap:wrap; gap:10px;">
                <div style="display:flex; align-items:center; gap:12px;">
                    <div>
                        <div style="font-size:13px; color:var(--text-muted);">Order #{{ $order->id }}</div>
                        <div style="font-size:12px; color:var(--text-muted);">
                            {{ $order->created_at->format('d M Y, h:i A') }}
                        </div>
                    </div>
                </div>

                {{-- Status badges --}}
                <div style="display:flex; align-items:center; gap:8px;">
                    <span style="font-size:11px; font-weight:700; padding:4px 12px; border-radius:20px;
                        background:{{ $order->statusColor() }}20; color:{{ $order->statusColor() }}; text-transform:uppercase; letter-spacing:0.5px;">
                        {{ $order->status }}
                    </span>
                    <span style="font-size:11px; font-weight:600; padding:4px 12px; border-radius:20px;
                        background:{{ $order->paymentColor() }}20; color:{{ $order->paymentColor() }};">
                        {{ ucfirst($order->payment_status) }} · {{ strtoupper($order->payment_method) }}
                    </span>
                </div>
            </div>

            {{-- Items preview --}}
            <div style="display:flex; gap:8px; margin-bottom:16px; flex-wrap:wrap;">
                @foreach($order->items->take(3) as $item)
                <div style="display:flex; align-items:center; gap:8px; background:rgba(255,255,255,0.03); border:1px solid var(--border); border-radius:8px; padding:8px 12px; font-size:12px;">
                    <span style="color:var(--text-muted);">{{ $item->quantity }}×</span>
                    <span>{{ Str::limit($item->product_name, 30) }}</span>
                    <span style="color:#a5b4fc; font-weight:600;">৳{{ number_format($item->price, 0) }}</span>
                </div>
                @endforeach
                @if($order->items->count() > 3)
                <div style="display:flex; align-items:center; padding:8px 12px; font-size:12px; color:var(--text-muted);">
                    +{{ $order->items->count() - 3 }} more items
                </div>
                @endif
            </div>

            {{-- Footer --}}
            <div style="display:flex; align-items:center; justify-content:space-between; border-top:1px solid var(--border); padding-top:14px;">
                <div style="font-size:15px; font-weight:700; color:#a5b4fc;">
                    Total: ৳{{ number_format($order->total, 0) }}
                </div>
                <div style="display:flex; gap:8px;">
                    <a href="{{ route('buyer.orders.show', $order) }}"
                       style="padding:8px 16px; background:rgba(99,102,241,0.1); color:#a5b4fc; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none; border:1px solid rgba(99,102,241,0.2);">
                        <i class="fa fa-eye"></i> View Details
                    </a>
                    @if($order->status === 'pending')
                    <form method="POST" action="{{ route('buyer.orders.cancel', $order) }}"
                          onsubmit="return confirm('Cancel this order?')">
                        @csrf @method('PATCH')
                        <button type="submit"
                            style="padding:8px 16px; background:rgba(239,68,68,0.1); color:#fca5a5; border-radius:8px; font-size:13px; font-weight:600; border:none; cursor:pointer;">
                            <i class="fa fa-xmark"></i> Cancel
                        </button>
                    </form>
                    @endif
                </div>
            </div>

        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div style="margin-top:20px;">
        {{ $orders->links() }}
    </div>

@else
    <div class="card" style="padding:60px; text-align:center;">
        <i class="fa fa-box-open" style="font-size:48px; color:var(--text-muted); opacity:0.3; display:block; margin-bottom:16px;"></i>
        <h3 style="font-size:18px; font-weight:600; margin-bottom:8px;">No orders yet</h3>
        <p style="color:var(--text-muted); margin-bottom:24px;">Start shopping to see your orders here!</p>
        <a href="{{ route('products.index') }}"
           style="display:inline-flex; align-items:center; gap:8px; padding:12px 24px; background:linear-gradient(135deg,#6366f1,#4f46e5); color:white; border-radius:12px; font-weight:600; text-decoration:none;">
            <i class="fa fa-bag-shopping"></i> Browse Products
        </a>
    </div>
@endif

@endsection
