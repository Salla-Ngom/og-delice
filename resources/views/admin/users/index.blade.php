@extends('layouts.admin')

@section('content')

<div class="max-w-7xl mx-auto py-10 px-6">

    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
            Gestion des Utilisateurs
        </h1>

        <span class="bg-gray-900 text-white px-4 py-2 rounded-xl shadow">
            Total : {{ $users->count() }}
        </span>
    </div>


    <div class="bg-white shadow-xl rounded-2xl border overflow-hidden">

        <table class="w-full text-left">
            <thead class="bg-gray-100 text-gray-600 text-sm uppercase">
                <tr>
                    <th class="p-4">Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Statut</th>
                    <th>Inscription</th>
                    <th class="text-right p-4">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @foreach($users as $user)
                <tr class="hover:bg-gray-50 transition">

                    <td class="p-4 font-semibold">
                        {{ $user->name }}
                    </td>

                    <td>{{ $user->email }}</td>

                    <td>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $user->role == 'admin'
                                ? 'bg-purple-100 text-purple-600'
                                : 'bg-blue-100 text-blue-600' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>

                    <td>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $user->is_active
                                ? 'bg-green-100 text-green-600'
                                : 'bg-red-100 text-red-600' }}">
                            {{ $user->is_active ? 'Actif' : 'Désactivé' }}
                        </span>
                    </td>

                    <td class="text-sm text-gray-500">
                        {{ $user->created_at?->diffForHumans() }}
                    </td>

                    <td class="text-right p-4 space-x-2">

                        <a href="{{ route('admin.users.show', $user) }}"
                           class="px-3 py-2 bg-gray-900 text-white rounded-lg text-sm hover:bg-gray-800">
                            Voir
                        </a>

                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="px-3 py-2 bg-orange-500 text-white rounded-lg text-sm hover:bg-orange-600">
                            Modifier
                        </a>

                    </td>

                </tr>
                @endforeach

            </tbody>
        </table>

    </div>

</div>

@endsection