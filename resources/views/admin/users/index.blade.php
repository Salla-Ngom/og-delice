@extends('layouts.admin')

@section('title', 'Utilisateurs')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Gestion des Utilisateurs</h1>
        {{-- ✅ ->total() pagination — pas ->count() qui retourne seulement la page courante --}}
        <a href="{{ route('admin.users.create') }}"
           class="bg-orange-500 text-white px-5 py-2 rounded-xl shadow hover:bg-orange-600 transition font-semibold text-sm">
            + Créer un utilisateur
        </a>
    </div>

    <div class="bg-white shadow-xl rounded-2xl border overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-gray-500 text-sm uppercase border-b">
                <tr>
                    <th class="p-4">Nom</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Rôle</th>
                    <th class="p-4">Statut</th>
                    <th class="p-4">Inscription</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($users as $user)
                    <tr class="hover:bg-gray-50 transition">

                        <td class="p-4 font-semibold text-gray-800">{{ $user->name }}</td>
                        <td class="p-4 text-gray-600">{{ $user->email }}</td>

                        <td class="p-4">
                            {{-- ✅ role_badge et role_label accessors du modèle --}}
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $user->role_badge }}">
                                {{ $user->role_label }}
                            </span>
                        </td>

                        <td class="p-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $user->status_badge }}">
                                {{ $user->status_label }}
                            </span>
                        </td>

                        <td class="p-4 text-sm text-gray-500">
                            {{ $user->created_at?->diffForHumans() }}
                        </td>

                        <td class="p-4 text-right">
                            <div class="flex justify-end items-center gap-2">

                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="px-3 py-1.5 bg-orange-500 text-white rounded-lg text-xs font-medium hover:bg-orange-600 transition">
                                    Modifier
                                </a>

                                {{-- Toggle statut --}}
                                <form method="POST" action="{{ route('admin.users.toggleStatus', $user) }}">
                                    @csrf
                                    <button type="submit"
                                            class="px-3 py-1.5 rounded-lg text-xs font-medium transition
                                                {{ $user->is_active
                                                    ? 'bg-red-100 text-red-600 hover:bg-red-200'
                                                    : 'bg-green-100 text-green-600 hover:bg-green-200' }}">
                                        {{ $user->is_active ? 'Désactiver' : 'Activer' }}
                                    </button>
                                </form>

                                {{-- Supprimer — caché pour soi-même --}}
                                @if($user->id !== auth()->id())
                                    <form method="POST"
                                          action="{{ route('admin.users.destroy', $user) }}"
                                          onsubmit="return confirm('Supprimer {{ addslashes($user->name) }} ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-1.5 bg-gray-100 text-red-600 rounded-lg text-xs font-medium hover:bg-red-50 transition">
                                            Supprimer
                                        </button>
                                    </form>
                                @endif

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div>{{ $users->links() }}</div>

</div>
@endsection
