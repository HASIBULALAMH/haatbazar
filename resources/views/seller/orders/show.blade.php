@extends('layouts.seller')

@section('title', 'Order #' . $order->id)

@section('content')

<div class="topbar">
    <div>
        <h1 class="topbar-title">Order #{{ $order->id }}</h1>
        <p class="topbar-subtitle">{{ $order->created_at->format('d M Y, h:i A') }}</p>
    </div>
    <a href="{{ route('seller.orders.index') }}"
       style="display:inline-flex; align-items:center; gap:6px; font-size:13px; color:var(--text-muted); text-decoration:none;">
        <i class="fa fa-arrow-left"></i> Back to Orders
    </a>
</div>

<div style="display:flex; flex-direction:column; gap:16px; max-width:860px;">

    {{-- Status Update --}}
    @if($order->status !== 'cancelled')
    <div class="card" style="padding:20px;">
        <h3 style="font-size:14px; font-weight:700; margin-bottom:16px;">
            <i class="fa fa-pen-to-square" style="color:#a5b4fc;"></i> Update Order Status
        </h3>
        <form method="POST" action="{{ route('seller.orders.status', $order) }}"
              style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            @csrf @method('PATCH')
            <select name="status" class="form-input" style="flex:1; min-width:180px;">
                @foreach(['pending','processing','shipped','delivered'] as $s)
                <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>
                    {{ ucfirst($s) }}
                </option>
                @endforeach
            </select>
            <button type="submit" class="btn-submit" style="padding:10px 24px; margin:0;">
                <i class="fa fa-check"></i> Update Status
            </button>
        </form>
    </div>
    @endif

    {{-- Order Status Tracker --}}
    <div class="card" style="padding:20px;">
        <h3 style="font-size:14px; font-weight:700; margin-bottom:16px;">
            <i class="fa fa-timeline" style="color:#a5b4fc;"></i> Order Progress
        </h3>
        <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
            @foreach(['pending','processing','shipped','delivered'] as $step)
            @php
                $statuses = ['pending','processing','shipped','delivered'];
                $currentIndex = array_search($order->status, $statuses);
                $stepIndex = array_search($step, $statuses);
                $isDone = $stepIndex <= $currentIndex && $order->status !== 'cancelled';
            @endphp
            <div style="display:flex; align-items:center; gap:8px;">
                <div style="display:flex; flex-direction:column; align-items:center; gap:4px;">
                    <div style="width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:12px;
                        background:{{ $isDone ? 'rgba(99,102,241,0.2)' : 'rgba(255,255,255,0.04)' }};
                        color:{{ $isDone ? '#a5b4fc' : 'var(--text-muted)' }};
                        border:1px solid {{ $isDone ? '#6366f1' : 'var(--border)' }};">
                        <i class="fa fa-{{ $step === 'pending' ? 'clock' : ($step === 'processing' ? 'gear' : ($step === 'shipped' ? 'truck' : 'circle-check')) }}"></i>
                    </div>
                    <span style="font-size:11px; color:{{ $isDone ? '#a5b4fc' : 'var(--text-muted)' }}; text-transform:capitalize;">{{ $step }}</span>
                </div>
                @if($step !== 'delivered')
                <div style="width:50px; height:1px; background:{{ $isDone ? '#6366f1' : 'var(--border)' }}; margin-bottom:16px;"></div>
                @endif
            </div>
            @endforeach
            @if($order->status === 'cancelled')
            <span style="font-size:12px; font-weight:700; color:#fca5a5; background:rgba(239,68,68,0.1); padding:4px 12px; border-radius:20px;">
                <i class="fa fa-xmark"></i> Cancelled
            </span>
            @endif
        </div>
    </div>

    {{-- Seller's Items Only --}}
    <div class="card" style="padding:20px;">
        <h3 style="font-size:14px; font-weight:700; margin-bottom:16px;">
            <i class="fa fa-box" style="color:#a5b4fc;"></i> Your Items in This Order
        </h3>
        <div style="display:flex; flex-direction:column; gap:12px;">
            @foreach($order->sellerItems as $item)
            <div style="display:flex; align-items:center; gap:14px; padding:12px; background:rgba(255,255,255,0.02); border-radius:10px; border:1px solid var(--border);">
                @if($item->product?->primaryImage)
                    <img src="{{ asset('storage/' . $item->product->primaryImage->image) }}"
                         style="width:60px; height:60px; object-fit:cover; border-radius:8px; flex-shrink:0;">
                @else
                    <div style="width:60px; height:60px; border-radius:8px; background:rgba(255,255,255,0.04); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <i class="fa fa-image" style="color:var(--text-muted);"></i>
                    </div>
                @endif
                <div style="flex:1;">
                    <div style="font-size:14px; font-weight:600; margin-bottom:4px;">{{ $item->product_name }}</div>
                    <div style="font-size:12px; color:var(--text-muted);">
                        {{ $item->quantity }} × ৳{{ number_format($item->price, 0) }}
                    </div>
                </div>
                <div style="font-size:15px; font-weight:700; color:#a5b4fc; flex-shrink:0;">
                    ৳{{ number_format($item->subtotal, 0) }}
                </div>
            </div>
            @endforeach
        </div>

        {{-- Items subtotal --}}
        <div style="border-top:1px solid var(--border); margin-top:14px; padding-top:14px; display:flex; justify-content:space-between; font-weight:700;">
            <span>Your Earnings</span>
            <span style="color:#86efac;">৳{{ number_format($order->sellerItems->sum('subtotal'), 0) }}</span>
        </div>
    </div>

    {{-- Buyer + Shipping Info --}}
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">

        <div class="card" style="padding:20px;">
            <h3 style="font-size:14px; font-weight:700; margin-bottom:14px;">
                <i class="fa fa-user" style="color:#a5b4fc;"></i> Buyer Info
            </h3>
            <div style="font-size:13px; color:var(--text-muted); line-height:2;">
                <div><span style="color:var(--white); font-weight:600;">{{ $order->buyer->name }}</span></div>
                <div><i class="fa fa-envelope" style="width:16px;"></i> {{ $order->buyer->email }}</div>
            </div>
        </div>

        <div class="card" style="padding:20px;">
            <h3 style="font-size:14px; font-weight:700; margin-bottom:14px;">
                <i class="fa fa-location-dot" style="color:#a5b4fc;"></i> Shipping Address
            </h3>
            <div style="font-size:13px; color:var(--text-muted); line-height:2;">
                <div><span style="color:var(--white); font-weight:600;">{{ $order->shipping_name }}</span></div>
                <div><i class="fa fa-phone" style="width:16px;"></i> {{ $order->shipping_phone }}</div>
                <div><i class="fa fa-map-pin" style="width:16px;"></i> {{ $order->shipping_address }}</div>
                <div><i class="fa fa-city" style="width:16px;"></i> {{ $order->shipping_city }}</div>
            </div>
        </div>

    </div>

    {{-- Notes --}}
    @if($order->notes)
    <div class="card" style="padding:16px;">
        <i class="fa fa-note-sticky" style="color:#a5b4fc;"></i>
        <span style="font-size:13px; color:var(--text-muted); margin-left:8px;">{{ $order->notes }}</span>
    </div>
    @endif

</div>

@endsection
