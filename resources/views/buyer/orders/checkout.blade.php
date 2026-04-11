@extends('layouts.buyer')

@section('title', 'Checkout')

@section('content')

<div class="topbar">
    <div>
        <h1 class="topbar-title">Checkout</h1>
        <p class="topbar-subtitle">Review your order and complete purchase</p>
    </div>
    <a href="{{ route('buyer.cart.index') }}"
       style="display:inline-flex; align-items:center; gap:6px; font-size:13px; color:var(--text-muted); text-decoration:none;">
        <i class="fa fa-arrow-left"></i> Back to Cart
    </a>
</div>

{{-- ── CART SUMMARY (Top) ─────────────────────────────────── --}}
<div class="card" style="margin-bottom:20px; padding:20px;">

    <h3 style="font-size:15px; font-weight:700; margin-bottom:16px; display:flex; align-items:center; gap:8px;">
        <i class="fa fa-cart-shopping" style="color:#a5b4fc;"></i> Order Summary
        <span style="font-size:12px; font-weight:400; color:var(--text-muted);">({{ $cartItems->count() }} items)</span>
    </h3>

    {{-- Items --}}
    <div style="display:flex; flex-direction:column; gap:12px; margin-bottom:16px;">
        @foreach($cartItems as $item)
        <div style="display:flex; align-items:center; gap:12px; padding:10px; background:rgba(255,255,255,0.02); border-radius:10px; border:1px solid var(--border);">

            {{-- Image --}}
            @if($item->product->primaryImage)
                <img src="{{ asset('storage/' . $item->product->primaryImage->image) }}"
                     style="width:52px; height:52px; object-fit:cover; border-radius:8px; flex-shrink:0;">
            @else
                <div style="width:52px; height:52px; border-radius:8px; background:rgba(255,255,255,0.04); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <i class="fa fa-image" style="color:var(--text-muted);"></i>
                </div>
            @endif

            {{-- Info --}}
            <div style="flex:1; min-width:0;">
                <div style="font-size:13px; font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                    {{ $item->product->name }}
                </div>
                <div style="font-size:12px; color:var(--text-muted); margin-top:2px;">
                    <i class="fa fa-store"></i> {{ $item->product->shop->name }}
                </div>
            </div>

            {{-- Qty × Price --}}
            <div style="text-align:right; flex-shrink:0;">
                <div style="font-size:12px; color:var(--text-muted);">{{ $item->quantity }} ×
                    ৳{{ number_format($item->product->discount_price ?? $item->product->price, 0) }}
                </div>
                <div style="font-size:14px; font-weight:700; color:#a5b4fc;">
                    ৳{{ number_format(($item->product->discount_price ?? $item->product->price) * $item->quantity, 0) }}
                </div>
            </div>

        </div>
        @endforeach
    </div>

    {{-- Total row --}}
    <div style="border-top:1px solid var(--border); padding-top:14px; display:flex; justify-content:space-between; align-items:center;">
        <div style="font-size:13px; color:var(--text-muted);">
            Shipping: <span style="color:#86efac; font-weight:600;">Free</span>
        </div>
        <div style="font-size:18px; font-weight:700; color:#a5b4fc;">
            Total: ৳{{ number_format($total, 0) }}
        </div>
    </div>

</div>

{{-- ── SHIPPING FORM (Bottom) ──────────────────────────────── --}}
<form method="POST" action="{{ route('buyer.orders.store') }}">
@csrf

    {{-- Address Option Toggle --}}
    @if($defaultAddress)
    <div class="card" style="margin-bottom:16px; padding:16px;">

        <h3 style="font-size:14px; font-weight:700; margin-bottom:14px; display:flex; align-items:center; gap:8px;">
            <i class="fa fa-location-dot" style="color:#a5b4fc;"></i> Shipping Address
        </h3>

        {{-- Option buttons --}}
        <div style="display:flex; gap:10px; margin-bottom:16px;">
            <button type="button" id="btn-use-saved"
                onclick="useAddress('saved')"
                style="flex:1; padding:10px; border-radius:10px; font-size:13px; font-weight:600; cursor:pointer; border:1px solid #6366f1; background:rgba(99,102,241,0.15); color:#a5b4fc; transition:all 0.2s;">
                <i class="fa fa-bookmark"></i> Use Saved Address
            </button>
            <button type="button" id="btn-use-manual"
                onclick="useAddress('manual')"
                style="flex:1; padding:10px; border-radius:10px; font-size:13px; font-weight:600; cursor:pointer; border:1px solid var(--border); background:transparent; color:var(--text-muted); transition:all 0.2s;">
                <i class="fa fa-pencil"></i> Enter Manually
            </button>
        </div>

        {{-- Saved address preview --}}
        <div id="saved-address-preview"
             style="padding:12px; background:rgba(99,102,241,0.06); border:1px solid rgba(99,102,241,0.2); border-radius:10px; font-size:13px; line-height:1.8;">
            <div style="font-weight:600; color:var(--white);">{{ $defaultAddress->name ?? Auth::guard('buyer')->user()->name }}</div>
            <div style="color:var(--text-muted);">{{ $defaultAddress->phone ?? '' }}</div>
            <div style="color:var(--text-muted);">{{ $defaultAddress->address }}, {{ $defaultAddress->city }}</div>
        </div>

        {{-- Hidden inputs for saved address --}}
        <div id="saved-inputs">
            <input type="hidden" name="shipping_name"    value="{{ $defaultAddress->name ?? Auth::guard('buyer')->user()->name }}">
            <input type="hidden" name="shipping_phone"   value="{{ $defaultAddress->phone ?? '' }}">
            <input type="hidden" name="shipping_address" value="{{ $defaultAddress->address }}">
            <input type="hidden" name="shipping_city"    value="{{ $defaultAddress->city }}">
        </div>

    </div>
    @endif

    {{-- Manual address form --}}
    <div class="card" id="manual-address-form"
         style="margin-bottom:16px; padding:20px; {{ $defaultAddress ? 'display:none;' : '' }}">

        <h3 style="font-size:14px; font-weight:700; margin-bottom:16px; display:flex; align-items:center; gap:8px;">
            <i class="fa fa-location-dot" style="color:#a5b4fc;"></i>
            {{ $defaultAddress ? 'Enter Address Manually' : 'Shipping Address' }}
        </h3>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">

            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="shipping_name"
                       class="form-input {{ $errors->has('shipping_name') ? 'is-invalid' : '' }}"
                       value="{{ old('shipping_name', Auth::guard('buyer')->user()->name) }}"
                       placeholder="Recipient name">
                @error('shipping_name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="text" name="shipping_phone"
                       class="form-input {{ $errors->has('shipping_phone') ? 'is-invalid' : '' }}"
                       value="{{ old('shipping_phone', Auth::guard('buyer')->user()->phone ?? '') }}"
                       placeholder="01XXXXXXXXX">
                @error('shipping_phone')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="grid-column:1/-1;">
                <label class="form-label">Full Address</label>
                <input type="text" name="shipping_address"
                       class="form-input {{ $errors->has('shipping_address') ? 'is-invalid' : '' }}"
                       value="{{ old('shipping_address') }}"
                       placeholder="House, Road, Area">
                @error('shipping_address')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">City</label>
                <input type="text" name="shipping_city"
                       class="form-input {{ $errors->has('shipping_city') ? 'is-invalid' : '' }}"
                       value="{{ old('shipping_city') }}"
                       placeholder="Dhaka">
                @error('shipping_city')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

        </div>
    </div>

    {{-- Payment + Notes --}}
    <div class="card" style="margin-bottom:16px; padding:20px;">

        <h3 style="font-size:14px; font-weight:700; margin-bottom:16px; display:flex; align-items:center; gap:8px;">
            <i class="fa fa-credit-card" style="color:#a5b4fc;"></i> Payment Method
        </h3>

        <div style="display:flex; gap:12px; margin-bottom:20px;">

            {{-- COD --}}
            <label id="label-cod"
                style="flex:1; padding:14px; border-radius:12px; border:1px solid #6366f1; background:rgba(99,102,241,0.1); cursor:pointer; transition:all 0.2s;">
                <input type="radio" name="payment_method" value="cod" checked
                       onchange="selectPayment('cod')" style="display:none;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:36px; height:36px; border-radius:8px; background:rgba(99,102,241,0.15); display:flex; align-items:center; justify-content:center;">
                        <i class="fa fa-money-bill" style="color:#a5b4fc;"></i>
                    </div>
                    <div>
                        <div style="font-size:13px; font-weight:700; color:var(--white);">Cash on Delivery</div>
                        <div style="font-size:11px; color:var(--text-muted);">Pay when delivered</div>
                    </div>
                </div>
            </label>

            {{-- bKash --}}
            <label id="label-bkash"
                style="flex:1; padding:14px; border-radius:12px; border:1px solid var(--border); background:transparent; cursor:pointer; transition:all 0.2s; opacity:0.5;">
                <input type="radio" name="payment_method" value="bkash"
                       onchange="selectPayment('bkash')" style="display:none;" disabled>
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:36px; height:36px; border-radius:8px; background:rgba(232,0,80,0.1); display:flex; align-items:center; justify-content:center;">
                        <i class="fa fa-mobile" style="color:#f9a8d4;"></i>
                    </div>
                    <div>
                        <div style="font-size:13px; font-weight:700; color:var(--white);">bKash</div>
                        <div style="font-size:11px; color:var(--text-muted);">Coming soon</div>
                    </div>
                </div>
            </label>

        </div>

        {{-- Notes --}}
        <div class="form-group">
            <label class="form-label">Order Notes <span style="color:var(--text-muted); font-weight:400;">(optional)</span></label>
            <textarea name="notes" class="form-input" rows="2"
                      placeholder="Special instructions for your order...">{{ old('notes') }}</textarea>
        </div>

    </div>

    {{-- Place Order Button --}}
    <button type="submit" class="btn-submit"
            style="width:100%; padding:16px; font-size:16px;">
        <i class="fa fa-bag-shopping"></i>
        Place Order — ৳{{ number_format($total, 0) }}
    </button>

</form>

@endsection

@push('scripts')
<script>
// ── Address toggle ────────────────────────────────────────
function useAddress(type) {
    const savedPreview  = document.getElementById('saved-address-preview');
    const savedInputs   = document.getElementById('saved-inputs');
    const manualForm    = document.getElementById('manual-address-form');
    const btnSaved      = document.getElementById('btn-use-saved');
    const btnManual     = document.getElementById('btn-use-manual');

    if (type === 'saved') {
        savedPreview.style.display  = 'block';
        savedInputs.style.display   = 'block';
        manualForm.style.display    = 'none';

        // Manual inputs disable করো — duplicate name conflict এড়াতে
        manualForm.querySelectorAll('input[name^="shipping"]').forEach(i => i.disabled = true);

        btnSaved.style.borderColor  = '#6366f1';
        btnSaved.style.background   = 'rgba(99,102,241,0.15)';
        btnSaved.style.color        = '#a5b4fc';
        btnManual.style.borderColor = 'var(--border)';
        btnManual.style.background  = 'transparent';
        btnManual.style.color       = 'var(--text-muted)';

    } else {
        savedPreview.style.display  = 'none';
        savedInputs.style.display   = 'none';
        manualForm.style.display    = 'block';

        // Manual inputs enable করো
        manualForm.querySelectorAll('input[name^="shipping"]').forEach(i => i.disabled = false);

        btnManual.style.borderColor = '#6366f1';
        btnManual.style.background  = 'rgba(99,102,241,0.15)';
        btnManual.style.color       = '#a5b4fc';
        btnSaved.style.borderColor  = 'var(--border)';
        btnSaved.style.background   = 'transparent';
        btnSaved.style.color        = 'var(--text-muted)';
    }
}

// ── Payment method toggle ─────────────────────────────────
function selectPayment(method) {
    const labelCod   = document.getElementById('label-cod');
    const labelBkash = document.getElementById('label-bkash');

    if (method === 'cod') {
        labelCod.style.borderColor   = '#6366f1';
        labelCod.style.background    = 'rgba(99,102,241,0.1)';
        labelBkash.style.borderColor = 'var(--border)';
        labelBkash.style.background  = 'transparent';
    }
}

// Page load এ default state set করো
document.addEventListener('DOMContentLoaded', function () {
    @if($defaultAddress)
        useAddress('saved');
    @else
        // Manual form inputs enable করো
        document.getElementById('manual-address-form')
            ?.querySelectorAll('input[name^="shipping"]')
            .forEach(i => i.disabled = false);
    @endif
});
</script>
@endpush
