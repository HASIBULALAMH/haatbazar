@extends('layouts.buyer')

@section('title', 'Order #' . $order->id)

@section('content')

{{-- Topbar --}}
<div class="topbar">
    <div>
        <h1 class="topbar-title">Order #{{ $order->id }}</h1>
        <p class="topbar-subtitle">{{ $order->created_at->format('d M Y, h:i A') }}</p>
    </div>
    <div style="display:flex; align-items:center; gap:10px;">
        <a href="{{ route('buyer.orders.invoice', $order) }}"
           style="display:inline-flex; align-items:center; gap:6px; padding:9px 18px;
                  background:linear-gradient(135deg,#6366f1,#4f46e5); color:white;
                  border-radius:10px; font-size:13px; font-weight:600; text-decoration:none;">
            <i class="fa fa-file-pdf"></i> Download Invoice
        </a>
        <a href="{{ route('buyer.orders.index') }}"
           style="display:inline-flex; align-items:center; gap:6px; font-size:13px;
                  color:var(--text-muted); text-decoration:none;">
            <i class="fa fa-arrow-left"></i> Back to Orders
        </a>
    </div>
</div>

<div style="display:flex; flex-direction:column; gap:16px;">

    {{-- Order Status Tracker --}}
    <div class="card" style="padding:20px;">
        <h3 style="font-size:14px; font-weight:700; margin-bottom:16px;">
            <i class="fa fa-timeline" style="color:#a5b4fc;"></i> Order Status
        </h3>
        <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
            @foreach(['pending','confirmed','processing','shipped','delivered'] as $step)
            @php
                $statuses     = ['pending','confirmed','processing','shipped','delivered'];
                $currentIndex = array_search($order->status, $statuses);
                $stepIndex    = array_search($step, $statuses);
                $isDone       = $stepIndex <= $currentIndex && $order->status !== 'cancelled';
            @endphp
            <div style="display:flex; align-items:center; gap:8px;">
                <div style="display:flex; flex-direction:column; align-items:center; gap:4px;">
                    <div style="width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:12px;
                        background:{{ $isDone ? 'rgba(99,102,241,0.2)' : 'rgba(255,255,255,0.04)' }};
                        color:{{ $isDone ? '#a5b4fc' : 'var(--text-muted)' }};
                        border:1px solid {{ $isDone ? '#6366f1' : 'var(--border)' }};">
                        <i class="fa fa-{{
                            $step === 'pending'    ? 'clock' :
                            ($step === 'confirmed'  ? 'circle-check' :
                            ($step === 'processing' ? 'gear' :
                            ($step === 'shipped'    ? 'truck' : 'circle-check')))
                        }}"></i>
                    </div>
                    <span style="font-size:11px; text-transform:capitalize;
                        color:{{ $isDone ? '#a5b4fc' : 'var(--text-muted)' }};">
                        {{ $step }}
                    </span>
                </div>
                @if($step !== 'delivered')
                <div style="width:40px; height:1px; margin-bottom:16px;
                    background:{{ $isDone ? '#6366f1' : 'var(--border)' }};"></div>
                @endif
            </div>
            @endforeach

            @if($order->status === 'cancelled')
            <span style="font-size:12px; font-weight:700; color:#fca5a5;
                background:rgba(239,68,68,0.1); padding:4px 12px; border-radius:20px;">
                <i class="fa fa-xmark"></i> Cancelled
            </span>
            @endif
        </div>
    </div>

    {{-- Items Ordered --}}
    <div class="card" style="padding:20px;">
        <h3 style="font-size:14px; font-weight:700; margin-bottom:16px;">
            <i class="fa fa-box" style="color:#a5b4fc;"></i> Items Ordered
        </h3>
        <div style="display:flex; flex-direction:column; gap:12px;">
            @foreach($order->items as $item)
            <div style="display:flex; align-items:center; gap:14px; padding:12px;
                background:rgba(255,255,255,0.02); border-radius:10px; border:1px solid var(--border);">

                @if($item->product?->primaryImage)
                    <img src="{{ asset('storage/' . $item->product->primaryImage->image) }}"
                         style="width:60px; height:60px; object-fit:cover; border-radius:8px; flex-shrink:0;">
                @else
                    <div style="width:60px; height:60px; border-radius:8px; background:rgba(255,255,255,0.04);
                        display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <i class="fa fa-image" style="color:var(--text-muted);"></i>
                    </div>
                @endif

                <div style="flex:1;">
                    <div style="font-size:14px; font-weight:600; margin-bottom:4px;">
                        {{ $item->product_name }}
                    </div>
                    <div style="font-size:12px; color:var(--text-muted);">
                        {{ $item->quantity }} × ৳{{ number_format($item->price, 0) }}
                        @if($item->original_price > $item->price)
                            <span style="text-decoration:line-through; margin-left:4px;">
                                ৳{{ number_format($item->original_price, 0) }}
                            </span>
                        @endif
                    </div>
                </div>

                <div style="font-size:15px; font-weight:700; color:#a5b4fc; flex-shrink:0;">
                    ৳{{ number_format($item->subtotal, 0) }}
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Order Summary + Payment --}}
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">

        <div class="card" style="padding:20px;">
            <h3 style="font-size:14px; font-weight:700; margin-bottom:14px;">
                <i class="fa fa-receipt" style="color:#a5b4fc;"></i> Order Summary
            </h3>
            <div style="display:flex; flex-direction:column; gap:10px; font-size:13px;">
                <div style="display:flex; justify-content:space-between;">
                    <span style="color:var(--text-muted);">Subtotal</span>
                    <span>৳{{ number_format($order->subtotal, 0) }}</span>
                </div>
                <div style="display:flex; justify-content:space-between;">
                    <span style="color:var(--text-muted);">Shipping</span>
                    <span style="color:#86efac;">Free</span>
                </div>
                <div style="border-top:1px solid var(--border); padding-top:10px;
                    display:flex; justify-content:space-between; font-weight:700; font-size:16px;">
                    <span>Total</span>
                    <span style="color:#a5b4fc;">৳{{ number_format($order->total, 0) }}</span>
                </div>
            </div>
        </div>

        <div class="card" style="padding:20px;">
            <h3 style="font-size:14px; font-weight:700; margin-bottom:14px;">
                <i class="fa fa-credit-card" style="color:#a5b4fc;"></i> Payment
            </h3>
            <div style="font-size:13px; display:flex; flex-direction:column; gap:10px;">
                <div style="display:flex; justify-content:space-between;">
                    <span style="color:var(--text-muted);">Method</span>
                    <span style="font-weight:600; text-transform:uppercase;">{{ $order->payment_method }}</span>
                </div>
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <span style="color:var(--text-muted);">Status</span>
                    <span style="font-size:11px; font-weight:700; padding:3px 10px; border-radius:20px;
                        background:{{ $order->paymentColor() }}20; color:{{ $order->paymentColor() }};">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
            </div>
        </div>

    </div>

    {{-- Shipping Address --}}
    <div class="card" style="padding:20px;">
        <h3 style="font-size:14px; font-weight:700; margin-bottom:14px;">
            <i class="fa fa-location-dot" style="color:#a5b4fc;"></i> Shipping Address
        </h3>
        <div style="font-size:13px; line-height:2; color:var(--text-muted);">
            <div><span style="color:var(--white); font-weight:600;">{{ $order->shipping_name }}</span></div>
            <div><i class="fa fa-phone" style="width:16px;"></i> {{ $order->shipping_phone }}</div>
            <div><i class="fa fa-map-pin" style="width:16px;"></i> {{ $order->shipping_address }}</div>
            <div><i class="fa fa-city" style="width:16px;"></i> {{ $order->shipping_city }}</div>
        </div>
        @if($order->notes)
        <div style="margin-top:12px; padding:10px; background:rgba(255,255,255,0.02);
            border-radius:8px; font-size:12px; color:var(--text-muted); border:1px solid var(--border);">
            <i class="fa fa-note-sticky"></i> {{ $order->notes }}
        </div>
        @endif
    </div>

    {{-- Cancel Button --}}
    @if($order->status === 'pending')
    <form method="POST" action="{{ route('buyer.orders.cancel', $order) }}"
          onsubmit="return confirm('Cancel this order?')">
        @csrf @method('PATCH')
        <button type="submit"
            style="width:100%; padding:12px; background:rgba(239,68,68,0.1); color:#fca5a5;
                   border-radius:10px; font-size:13px; font-weight:600;
                   border:1px solid rgba(239,68,68,0.2); cursor:pointer;">
            <i class="fa fa-xmark"></i> Cancel Order
        </button>
    </form>
    @endif

</div>

@endsection
