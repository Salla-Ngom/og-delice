@extends('layouts.admin')

@section('content')

<div class="max-w-4xl mx-auto py-10 px-6">

    <div class="bg-white shadow-xl rounded-2xl p-8 border">

        <h1 class="text-2xl font-bold mb-6">
            Détails utilisateur
        </h1>

        <div class="space-y-4">

            <p><strong>Nom :</strong> {{ $user->name }}</p>
            <p><strong>Email :</strong> {{ $user->email }}</p>
            <p><strong>Rôle :</strong> {{ ucfirst($user->role) }}</p>
            <p><strong>Statut :</strong>
                {{ $user->is_active ? 'Actif' : 'Désactivé' }}
            </p>
            <p><strong>Inscrit le :</strong>
                {{ $user->created_at?->format('d M Y à H:i') }}
            </p>

        </div>

        <div class="mt-8">
            <a href="{{ route('admin.users.index') }}"
               class="px-5 py-2 bg-gray-900 text-white rounded-lg">
                ← Retour
            </a>
        </div>

    </div>

</div>

@endsection