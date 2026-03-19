@extends('layouts.admin')

@section('title', 'Modifier ' . $user->name)

@section('content')
<div class="max-w-4xl mx-auto py-10 px-6">
    <div class="bg-white shadow-xl rounded-2xl p-8 border">

        <div class="flex justify-between items-start mb-6">
            <h1 class="text-2xl font-bold">Modifier utilisateur</h1>
            <a href="{{ route('admin.users.index') }}"
               class="text-sm text-gray-500 hover:text-gray-800 transition">← Retour</a>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block mb-2 font-semibold text-sm text-gray-700">Nom</label>
                <input type="text" name="name"
                       value="{{ old('name', $user->name) }}"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-500 focus:outline-none @error('name') border-red-400 @enderror">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block mb-2 font-semibold text-sm text-gray-700">Email</label>
                <input type="email" name="email"
                       value="{{ old('email', $user->email) }}"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-500 focus:outline-none @error('email') border-red-400 @enderror">
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block mb-2 font-semibold text-sm text-gray-700">Rôle</label>
                <select name="role"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    {{-- ✅ $allowedRoles passé par le contrôleur selon le rôle du connecté --}}
                    @foreach($allowedRoles as $val => $label)
                        <option value="{{ $val }}" {{ old('role', $user->role) === $val ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Mot de passe optionnel --}}
            <div class="border border-dashed border-gray-300 rounded-xl p-5">
                <p class="text-sm font-semibold text-gray-700 mb-4">
                    Nouveau mot de passe <span class="font-normal text-gray-400">(laisser vide pour ne pas modifier)</span>
                </p>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Mot de passe</label>
                        <input type="password" name="password"
                               class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-500 focus:outline-none @error('password') border-red-400 @enderror">
                        @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Confirmation</label>
                        <input type="password" name="password_confirmation"
                               class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    </div>
                </div>
            </div>

            {{-- ✅ is_active retiré du formulaire edit — géré exclusivement par toggleStatus()
                 ❌ SUPPRIMÉ : <select name="is_active"> qui permettait de contourner les protections du contrôleur --}}

            <div class="mt-8 flex gap-4">
                <button type="submit"
                        class="px-6 py-3 bg-orange-600 text-white rounded-xl hover:bg-orange-700 transition font-semibold">
                    Mettre à jour
                </button>
                <a href="{{ route('admin.users.index') }}"
                   class="px-6 py-3 bg-gray-100 rounded-xl hover:bg-gray-200 transition text-gray-700">
                    Annuler
                </a>
            </div>

        </form>
    </div>
</div>
@endsection
