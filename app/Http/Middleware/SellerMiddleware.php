<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('seller')->check()) {
            return redirect()->route('seller.login');
        }

        if (Auth::guard('seller')->user()->role !== 'seller') {
            Auth::guard('seller')->logout();
            return redirect()->route('seller.login');
        }

        return $next($request);
    }
}
