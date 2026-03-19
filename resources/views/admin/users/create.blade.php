@extends('layouts.admin')

@section('title', 'Créer un utilisateur')

@section('content')
<div class="max-w-2xl mx-auto py-10 px-6">
    <div class="bg-white shadow-xl rounded-2xl p-8 border">

        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-2xl font-bold">Créer un utilisateur</h1>
                @if(auth()->user()->role !== 'super_admin')
                    <p class="text-xs text-gray-400 mt-1">Vous pouvez uniquement créer des vendeurs.</p>
                @endif
            </div>
            <a href="{{ route('admin.users.index') }}"
               class="text-sm text-gray-500 hover:text-gray-800 transition">← Retour</a>
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block mb-1.5 font-semibold text-sm text-gray-700">Nom complet</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full border rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none @error('name') border-red-400 @enderror">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block mb-1.5 font-semibold text-sm text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="w-full border rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none @error('email') border-red-400 @enderror">
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- ✅ Rôles disponibles selon le rôle du créateur --}}
            <div>
                <label class="block mb-1.5 font-semibold text-sm text-gray-700">Rôle</label>
                <select name="role"
                        class="w-full border rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none @error('role') border-red-400 @enderror">
                    <option value="">-- Choisir --</option>
                    @foreach($allowedRoles as $val => $label)
                        <option value="{{ $val }}" {{ old('role') === $val ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('role')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1.5 font-semibold text-sm text-gray-700">Mot de passe</label>
                    <input type="password" name="password"
                           class="w-full border rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none @error('password') border-red-400 @enderror">
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block mb-1.5 font-semibold text-sm text-gray-700">Confirmation</label>
                    <input type="password" name="password_confirmation"
                           class="w-full border rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none">
                </div>
            </div>

            <div class="pt-2 flex gap-3">
                <button type="submit"
                        class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-8 py-3 rounded-xl transition shadow">
                    Créer l'utilisateur
                </button>
                <a href="{{ route('admin.users.index') }}"
                   class="border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold px-6 py-3 rounded-xl transition">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
