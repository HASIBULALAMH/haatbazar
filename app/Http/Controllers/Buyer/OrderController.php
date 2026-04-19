<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Mail\NewOrderForSeller;
use App\Mail\OrderPlaced;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    /**
     * Checkout page।
     */
    public function checkout()
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('buyer')->user();

        $cartItems = $user->cartItems()
            ->with(['product.primaryImage', 'product.shop'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('buyer.cart.index')
                ->with('error', 'Your cart is empty!');
        }

        $total = $cartItems->sum(
            fn($item) => ($item->product->discount_price ?? $item->product->price) * $item->quantity
        );

        $defaultAddress = $user->addresses()
            ->where('is_default', true)
            ->first();

        return view('buyer.orders.checkout', compact('cartItems', 'total', 'defaultAddress'));
    }

    /**
     * Order place করো।
     */
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('buyer')->user();

        $request->validate([
            'shipping_name'    => 'required|string|max:100',
            'shipping_phone'   => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'shipping_city'    => 'required|string|max:100',
            'payment_method'   => 'required|in:cod,bkash',
            'notes'            => 'nullable|string|max:500',
        ]);

        $cartItems = $user->cartItems()
            ->with(['product.shop'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('buyer.cart.index')
                ->with('error', 'Your cart is empty!');
        }

        foreach ($cartItems as $item) {
            if ($item->quantity > $item->product->stock) {
                return redirect()->route('buyer.cart.index')
                    ->with('error', "{$item->product->name} has insufficient stock!");
            }
        }

        $subtotal = $cartItems->sum(
            fn($item) => ($item->product->discount_price ?? $item->product->price) * $item->quantity
        );

        // Transaction
        $order = null;
        DB::transaction(function () use ($user, $request, $cartItems, $subtotal, &$order) {

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

                $item->product->decrement('stock', $item->quantity);
            }

            $user->cartItems()->delete();
        });

        // Buyer email — Order Placed
        try {
            /** @var Order $order */
            $order->load('items');
            Mail::to($user->email)->send(new OrderPlaced($order));
        } catch (\Exception $e) {
            Log::error('Order placed email failed: ' . $e->getMessage());
        }

        // Seller email — New Order
        try {
            $order->load('items.seller');

            $sellerEmails = $order->items
                ->pluck('seller.email')
                ->filter()
                ->unique()
                ->values();

            foreach ($sellerEmails as $sellerEmail) {
                Mail::to($sellerEmail)->send(new NewOrderForSeller($order));
            }
        } catch (\Exception $e) {
            Log::error('Seller email failed: ' . $e->getMessage());
        }

        return redirect()->route('buyer.orders.index')
            ->with('success', 'Order placed successfully! 🎉');
    }

    /**
     * Buyer এর সব orders।
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

        if ($order->user_id !== $user->id) {
            abort(403);
        }

        $order->load('items.product.primaryImage');

        return view('buyer.orders.show', compact('order'));
    }

    /**
     * Order cancel।
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
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }
            $order->update(['status' => 'cancelled']);
        });

        return back()->with('success', 'Order cancelled successfully!');
    }

    /**
     * Order invoice PDF download।
     */
    public function invoice(Order $order)
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('buyer')->user();

        if ($order->user_id !== $user->id) {
            abort(403);
        }

        $order->load('items', 'buyer');

        $pdf = Pdf::loadView('buyer.orders.invoice', compact('order'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('invoice-order-' . $order->id . '.pdf');
    }
}
