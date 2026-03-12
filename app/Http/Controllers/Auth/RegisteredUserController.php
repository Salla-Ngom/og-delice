<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\SenegalPhone;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'email'            => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            // ✅ Validation numéro sénégalais via règle dédiée
            'phone'            => ['required', new SenegalPhone()],
            'delivery_address' => ['nullable', 'string', 'max:500'],
            'password'         => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user                   = new User();
        $user->name             = $request->name;
        $user->email            = $request->email;
        // ✅ Normaliser : supprimer espaces et +221 avant stockage
        $user->phone            = SenegalPhone::normalize($request->phone);
        $user->delivery_address = $request->delivery_address;
        $user->password         = Hash::make($request->password);
        $user->role             = 'client';
        $user->is_active        = true;
        $user->save();

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('client.dashboard');
    }
}