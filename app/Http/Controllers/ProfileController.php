<?php

namespace App\Http\Controllers;

use App\Rules\SenegalPhone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        // ✅ Validation inline — plus besoin de ProfileUpdateRequest séparé
        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'email'            => ['required', 'email', 'max:255',
                                   Rule::unique('users')->ignore($request->user()->id)],
            // ✅ Téléphone sénégalais optionnel
            'phone'            => ['nullable', new SenegalPhone],
            // ✅ Adresse de livraison optionnelle
            'delivery_address' => ['nullable', 'string', 'max:500'],
        ]);

        $user = $request->user();

        $user->name  = $validated['name'];
        $user->email = $validated['email'];

        // Normalise le téléphone avant stockage : "77 123 45 67" → "771234567"
        $user->phone = isset($validated['phone'])
            ? SenegalPhone::normalize($validated['phone'])
            : null;

        $user->delivery_address = $validated['delivery_address'] ?? null;

        // Révoquer la vérification email si l'adresse a changé
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}