<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VendeurMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'vendeur'])) {
            abort(403, 'Accès réservé aux vendeurs.');
        }

        if (!auth()->user()->is_active) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Votre compte est désactivé.');
        }

        return $next($request);
    }
}
