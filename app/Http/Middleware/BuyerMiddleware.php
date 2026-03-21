<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('buyer')->check()) {
            return redirect()->route('buyer.login');
        }

        if (Auth::guard('buyer')->user()->role !== 'buyer') {
            Auth::guard('buyer')->logout();
            return redirect()->route('buyer.login');
        }

        return $next($request);
    }
}
