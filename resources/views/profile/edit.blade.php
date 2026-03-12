@extends('layouts.client')

@section('title', 'Mon Profil')

@section('content')
<div class="max-w-3xl mx-auto py-12 px-6 space-y-6">

    <div class="flex items-center justify-between mb-2">
        <h1 class="text-3xl font-bold text-gray-800">Mon Profil</h1>
        <a href="{{ route('client.dashboard') }}" class="text-sm text-gray-500 hover:text-orange-500 transition">
            ← Retour au tableau de bord
        </a>
    </div>

    {{-- FLASH --}}
    @if(session('status') === 'profile-updated')
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            ✅ Profil mis à jour avec succès.
        </div>
    @endif

    {{-- INFORMATIONS PERSONNELLES --}}
    <div class="bg-white rounded-2xl shadow border p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-1">Informations personnelles</h2>
        <p class="text-sm text-gray-400 mb-6">Nom, email, téléphone et adresse de livraison.</p>

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
            @csrf
            @method('PATCH')

            {{-- NOM --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                <input id="name" name="name" type="text"
                       value="{{ old('name', $user->name) }}"
                       required autofocus
                       class="w-full border rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none
                              @error('name') border-red-400 @enderror">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- EMAIL --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse email</label>
                <input id="email" name="email" type="email"
                       value="{{ old('email', $user->email) }}"
                       required
                       class="w-full border rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none
                              @error('email') border-red-400 @enderror">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- TÉLÉPHONE --}}
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                    Téléphone 🇸🇳
                    <span class="text-gray-400 font-normal">(optionnel)</span>
                </label>
                <div class="flex rounded-xl overflow-hidden border focus-within:ring-2 focus-within:ring-orange-400
                            @error('phone') border-red-400 @enderror">
                    <span class="bg-gray-50 border-r px-3 flex items-center text-sm text-gray-500 shrink-0">+221</span>
                    <input id="phone" name="phone" type="tel"
                           value="{{ old('phone', $user->formatted_phone) }}"
                           placeholder="77 123 45 67"
                           maxlength="11"
                           inputmode="numeric"
                           class="flex-1 px-4 py-2.5 text-sm focus:outline-none bg-white">
                </div>
                @error('phone')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-400 mt-1">Formats acceptés : 77, 78, 76, 70, 71, 72 + 7 chiffres</p>
            </div>

            {{-- ADRESSE DE LIVRAISON --}}
            <div>
                <label for="delivery_address" class="block text-sm font-medium text-gray-700 mb-1">
                    Adresse de livraison
                    <span class="text-gray-400 font-normal">(optionnel)</span>
                </label>
                <textarea id="delivery_address" name="delivery_address"
                          rows="2"
                          placeholder="Ex : Sacré-Cœur 3, Villa 42, Dakar"
                          class="w-full border rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none resize-none
                                 @error('delivery_address') border-red-400 @enderror">{{ old('delivery_address', $user->delivery_address) }}</textarea>
                @error('delivery_address')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-2">
                <button type="submit"
                        class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-8 py-3 rounded-xl transition shadow">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>

    {{-- MOT DE PASSE --}}
    <div class="bg-white rounded-2xl shadow border p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-1">Changer le mot de passe</h2>
        <p class="text-sm text-gray-400 mb-6">Utilise un mot de passe long et unique.</p>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe actuel</label>
                <input id="current_password" name="current_password" type="password"
                       autocomplete="current-password"
                       class="w-full border rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none
                              @if($errors->updatePassword->has('current_password')) border-red-400 @endif">
                @if($errors->updatePassword->has('current_password'))
                    <p class="text-red-500 text-xs mt-1">{{ $errors->updatePassword->first('current_password') }}</p>
                @endif
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
                <input id="password" name="password" type="password"
                       autocomplete="new-password"
                       class="w-full border rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none
                              @if($errors->updatePassword->has('password')) border-red-400 @endif">
                @if($errors->updatePassword->has('password'))
                    <p class="text-red-500 text-xs mt-1">{{ $errors->updatePassword->first('password') }}</p>
                @endif
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
                <input id="password_confirmation" name="password_confirmation" type="password"
                       autocomplete="new-password"
                       class="w-full border rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none">
            </div>

            <div class="flex items-center gap-4 pt-2">
                <button type="submit"
                        class="bg-gray-800 hover:bg-gray-900 text-white font-semibold px-8 py-3 rounded-xl transition shadow">
                    Mettre à jour
                </button>
                @if(session('status') === 'password-updated')
                    <p x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)"
                       class="text-sm text-green-600">✅ Mot de passe mis à jour.</p>
                @endif
            </div>
        </form>
    </div>

    {{-- SUPPRIMER LE COMPTE --}}
    <div class="bg-white rounded-2xl shadow border border-red-100 p-6" x-data="{ confirm: false }">
        <h2 class="text-lg font-semibold text-red-600 mb-1">Supprimer mon compte</h2>
        <p class="text-sm text-gray-400 mb-5">
            Cette action est irréversible. Toutes vos données seront supprimées définitivement.
        </p>

        <button @click="confirm = true"
                x-show="!confirm"
                class="bg-red-500 hover:bg-red-600 text-white font-semibold px-6 py-2.5 rounded-xl transition text-sm">
            Supprimer mon compte
        </button>

        {{-- Confirmation inline — pas de modale Alpine complexe --}}
        <div x-show="confirm" x-transition class="border border-red-200 rounded-xl p-5 bg-red-50 space-y-4">
            <p class="text-sm font-medium text-red-700">
                ⚠️ Confirme la suppression en entrant ton mot de passe.
            </p>
            <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-4">
                @csrf
                @method('DELETE')

                <input name="password" type="password"
                       placeholder="Ton mot de passe"
                       class="w-full border border-red-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-400 focus:outline-none">

                @if($errors->userDeletion->has('password'))
                    <p class="text-red-500 text-xs">{{ $errors->userDeletion->first('password') }}</p>
                @endif

                <div class="flex gap-3">
                    <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2.5 rounded-xl transition text-sm">
                        Confirmer la suppression
                    </button>
                    <button type="button" @click="confirm = false"
                            class="bg-white border text-gray-600 hover:bg-gray-50 font-semibold px-6 py-2.5 rounded-xl transition text-sm">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
// Formatage auto téléphone : "771234567" → "77 123 45 67"
document.getElementById('phone')?.addEventListener('input', function () {
    let val = this.value.replace(/\D/g, '').slice(0, 9);
    if (val.length > 2) val = val.slice(0,2) + ' ' + val.slice(2);
    if (val.length > 6) val = val.slice(0,6) + ' ' + val.slice(6);
    if (val.length > 9) val = val.slice(0,9) + ' ' + val.slice(9);
    this.value = val;
});
</script>
@endsection