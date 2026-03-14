<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // ✅ Vérifier is_active AVANT de régénérer la session
        if (!auth()->user()->is_active) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => 'Votre compte a été désactivé. Contactez l\'administrateur.']);
        }

        $request->session()->regenerate();

        // ✅ Redirection par rôle
        return match(auth()->user()->role) {
            'admin'   => redirect()->intended(route('admin.dashboard')),
            'vendeur' => redirect()->intended(route('vendeur.pos')),   // ← caisse POS
            default   => redirect()->intended(route('client.dashboard')),
        };
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
