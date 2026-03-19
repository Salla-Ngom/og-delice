<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->role !== 'super_admin') {
            abort(403, 'Accès réservé au super administrateur.');
        }

        if (!auth()->user()->is_active) {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'Votre compte est désactivé.');
        }

        return $next($request);
    }
}
