<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): \Symfony\Component\HttpFoundation\Response
    {
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return match($guard) {
                    'admin'  => redirect()->route('admin.dashboard'),
                    'seller' => redirect()->route('seller.dashboard'),
                    'buyer'  => redirect()->route('buyer.dashboard'),
                    default  => redirect('/'),
                };
            }
        }
        return $next($request);
    }
}
