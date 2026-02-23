@extends('layouts.admin')

@section('content')

<div class="max-w-4xl mx-auto py-10 px-6">

    <div class="bg-white shadow-xl rounded-2xl p-8 border">

        <h1 class="text-2xl font-bold mb-6">
            Modifier utilisateur
        </h1>

        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')

            <div class="space-y-6">

                <div>
                    <label class="block mb-2">Nom</label>
                    <input type="text" name="name"
                           value="{{ old('name',$user->name) }}"
                           class="w-full border rounded-lg px-4 py-2">
                </div>

                <div>
                    <label class="block mb-2">Email</label>
                    <input type="email" name="email"
                           value="{{ old('email',$user->email) }}"
                           class="w-full border rounded-lg px-4 py-2">
                </div>

                <div>
                    <label class="block mb-2">Rôle</label>
                    <select name="role"
                            class="w-full border rounded-lg px-4 py-2">
                        <option value="vendeur"
                            {{ $user->role == 'vendeur' ? 'selected' : '' }}>
                            Vendeur
                        </option>
                        <option value="admin"
                            {{ $user->role == 'admin' ? 'selected' : '' }}>
                            Admin
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block mb-2">Statut</label>
                    <select name="is_active"
                            class="w-full border rounded-lg px-4 py-2">
                        <option value="1"
                            {{ $user->is_active ? 'selected' : '' }}>
                            Actif
                        </option>
                        <option value="0"
                            {{ !$user->is_active ? 'selected' : '' }}>
                            Désactivé
                        </option>
                    </select>
                </div>

            </div>

            <div class="mt-8">
                <button class="px-6 py-3 bg-orange-600 text-white rounded-xl hover:bg-orange-700">
                    Mettre à jour
                </button>
            </div>

        </form>

    </div>

</div>

@endsection