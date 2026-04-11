<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    /**
     * Checkout page — cart items + shipping form দেখাবে।
     */
    public function checkout()
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('buyer')->user();

        // Cart empty হলে cart page এ redirect
        $cartItems = $user->cartItems()
            ->with(['product.primaryImage', 'product.shop'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('buyer.cart.index')
                ->with('error', 'Your cart is empty!');
        }

        // Total calculate
        $total = $cartItems->sum(
            fn($item) => ($item->product->discount_price ?? $item->product->price) * $item->quantity
        );

        // Default address auto-fill এর জন্য
        $defaultAddress = $user->addresses()
            ->where('is_default', true)
            ->first();

        return view('buyer.orders.checkout', compact('cartItems', 'total', 'defaultAddress'));
    }


    /**
     * Order place করো — cart থেকে order তৈরি করে cart clear করো।
     */
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('buyer')->user();

        // Validate shipping info
        $request->validate([
            'shipping_name'    => 'required|string|max:100',
            'shipping_phone'   => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'shipping_city'    => 'required|string|max:100',
            'payment_method'   => 'required|in:cod,bkash',
            'notes'            => 'nullable|string|max:500',
        ]);

        // Cart items নাও
        $cartItems = $user->cartItems()
            ->with(['product.shop'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('buyer.cart.index')
                ->with('error', 'Your cart is empty!');
        }

        // Stock check
        foreach ($cartItems as $item) {
            if ($item->quantity > $item->product->stock) {
                return redirect()->route('buyer.cart.index')
                    ->with('error', "{$item->product->name} has insufficient stock!");
            }
        }

        // Subtotal calculate
        $subtotal = $cartItems->sum(
            fn($item) => ($item->product->discount_price ?? $item->product->price) * $item->quantity
        );

        // Transaction — error হলে rollback
        DB::transaction(function () use ($user, $request, $cartItems, $subtotal) {

            $order = Order::create([
                'user_id'          => $user->id,
                'shipping_name'    => $request->shipping_name,
                'shipping_phone'   => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_city'    => $request->shipping_city,
                'payment_method'   => $request->payment_method,
                'payment_status'   => 'pending',
                'status'           => 'pending',
                'subtotal'         => $subtotal,
                'shipping_charge'  => 0,
                'total'            => $subtotal,
                'notes'            => $request->notes,
            ]);

            // Order items + stock কমাও
            foreach ($cartItems as $item) {
                $price = $item->product->discount_price ?? $item->product->price;

                OrderItem::create([
                    'order_id'       => $order->id,
                    'product_id'     => $item->product_id,
                    'seller_id'      => $item->product->shop->user_id,
                    'product_name'   => $item->product->name,
                    'price'          => $price,
                    'original_price' => $item->product->price,
                    'quantity'       => $item->quantity,
                    'subtotal'       => $price * $item->quantity,
                ]);

                // Stock কমাও
                $item->product->decrement('stock', $item->quantity);
            }

            // Cart clear
            $user->cartItems()->delete();
        });

        return redirect()->route('buyer.orders.index')
            ->with('success', 'Order placed successfully! 🎉');
    }

    /**
     * Buyer এর সব orders list।
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('buyer')->user();

        $orders = $user->orders()
            ->with('items')
            ->latest()
            ->paginate(10);

        return view('buyer.orders.index', compact('orders'));
    }

    /**
     * Single order detail।
     */
    public function show(Order $order)
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('buyer')->user();

        // অন্য buyer এর order দেখতে পারবে না
        if ($order->user_id !== $user->id) {
            abort(403);
        }

        $order->load('items.product.primaryImage');

        return view('buyer.orders.show', compact('order'));
    }

    /**
     * Order cancel — শুধু pending order।
     */
    public function cancel(Order $order)
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('buyer')->user();

        if ($order->user_id !== $user->id) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return back()->with('error', 'Only pending orders can be cancelled!');
        }

        DB::transaction(function () use ($order) {
            // Stock ফিরিয়ে দাও
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }
            $order->update(['status' => 'cancelled']);
        });

        return back()->with('success', 'Order cancelled successfully!');
    }
}
