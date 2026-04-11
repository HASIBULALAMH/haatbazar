<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Seller এর shop এ যত orders এসেছে সব দেখাবে।
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $seller */
        $seller = Auth::guard('seller')->user();

        // যে orders এ এই seller এর product আছে
        $query = Order::whereHas('items', function ($q) use ($seller) {
            $q->where('seller_id', $seller->id);
        })
            ->with([
                'buyer:id,name,email',
                'items' => fn($q) => $q->where('seller_id', $seller->id),
            ])
            ->latest();

        // Status filter
        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10)->withQueryString();

        // Blade এ $order->sellerItems use করার জন্য
        $orders->each(function ($order) {
            $order->setRelation('sellerItems', $order->items);
        });

        return view('seller.orders.index', compact('orders'));
    }
    
    /**
     * Single order detail — seller শুধু তার নিজের items দেখবে।
     */
    public function show(Order $order)
    {
        /** @var \App\Models\User $seller */
        $seller = Auth::guard('seller')->user();

        // এই order এ seller এর কোনো item আছে কিনা check করো
        $hasItem = $order->items()->where('seller_id', $seller->id)->exists();

        if (!$hasItem) {
            abort(403);
        }

        // Seller এর items only load করো
        $order->load([
            'buyer:id,name,email',
            'items' => fn($q) => $q->where('seller_id', $seller->id)
                ->with('product.primaryImage'),
        ]);

        // sellerItems relation blade এ use করতে পারবে
        $order->setRelation('sellerItems', $order->items);

        return view('seller.orders.show', compact('order'));
    }

    /**
     * Order status update করো।
     */
    public function updateStatus(Request $request, Order $order)
    {
        /** @var \App\Models\User $seller */
        $seller = Auth::guard('seller')->user();

        // Permission check
        $hasItem = $order->items()->where('seller_id', $seller->id)->exists();
        if (!$hasItem) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered',
        ]);

        // Cancelled order update করা যাবে না
        if ($order->status === 'cancelled') {
            return back()->with('error', 'Cancelled orders cannot be updated!');
        }

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Order status updated to ' . ucfirst($request->status) . '!');
    }
}
