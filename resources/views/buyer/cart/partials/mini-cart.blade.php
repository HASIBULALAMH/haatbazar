{{-- Mini-cart partial — AJAX fragment হিসেবেও use হয় --}}
@if($cartItems->count() > 0)

    {{-- Item List --}}
    <div class="mini-cart-items">
        @foreach($cartItems as $item)
        <div class="mini-cart-item">

            {{-- Image --}}
            <a href="{{ route('products.show', $item->product->slug) }}" class="mini-cart-img-link">
                @if($item->product->primaryImage)
                    <img src="{{ asset('storage/' . $item->product->primaryImage->image) }}"
                         alt="{{ $item->product->name }}"
                         class="mini-cart-img">
                @else
                    <div class="mini-cart-img-placeholder">
                        <i class="fa fa-image"></i>
                    </div>
                @endif
            </a>

            {{-- Info --}}
            <div class="mini-cart-item-info">
                <a href="{{ route('products.show', $item->product->slug) }}" class="mini-cart-item-name">
                    {{ Str::limit($item->product->name, 26) }}
                </a>
                <div class="mini-cart-item-meta">
                    <span class="mini-cart-qty">{{ $item->quantity }}×</span>
                    <span class="mini-cart-price">
                        ৳{{ number_format($item->product->discount_price ?? $item->product->price, 0) }}
                    </span>
                </div>
            </div>

            {{-- Line Total --}}
            <div class="mini-cart-item-total">
                ৳{{ number_format(($item->product->discount_price ?? $item->product->price) * $item->quantity, 0) }}
            </div>

        </div>
        @endforeach
    </div>

    {{-- Footer --}}
    <div class="mini-cart-footer">
        <div class="mini-cart-subtotal">
            <span>Subtotal</span>
            <span class="mini-cart-subtotal-price">৳{{ number_format($total, 0) }}</span>
        </div>
        <a href="{{ route('buyer.cart.index') }}" class="mini-cart-btn-view">
            <i class="fa fa-cart-shopping"></i> View Cart
        </a>
        <a href="{{ route('buyer.orders.checkout') }}" class="mini-cart-btn-checkout">
            Proceed to Checkout <i class="fa fa-arrow-right"></i>
        </a>
    </div>

@else

    {{-- Empty State --}}
    <div class="mini-cart-empty">
        <i class="fa fa-cart-shopping"></i>
        <p>Your cart is empty</p>
        <a href="{{ route('products.index') }}">Browse Products</a>
    </div>

@endif
