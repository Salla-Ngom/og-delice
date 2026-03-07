@extends('layouts.admin')

@section('title', 'Commandes')

@section('content')
<div class="space-y-6">

    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Commandes</h1>
            <p class="text-gray-500">Gestion des commandes clients</p>
        </div>
    </div>

    {{-- ✅ Filtres par statut --}}
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.orders.index') }}"
           class="px-4 py-2 rounded-full text-sm font-medium transition
                  {{ !request('status') ? 'bg-orange-500 text-white' : 'bg-white border text-gray-600 hover:border-orange-400' }}">
            Toutes
        </a>
        @foreach(['en_attente' => 'En attente', 'en_preparation' => 'En préparation', 'prete' => 'Prêtes', 'annulee' => 'Annulées'] as $val => $label)
            <a href="{{ route('admin.orders.index', ['status' => $val]) }}"
               class="px-4 py-2 rounded-full text-sm font-medium transition
                      {{ request('status') === $val ? 'bg-orange-500 text-white' : 'bg-white border text-gray-600 hover:border-orange-400' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="bg-white shadow-xl rounded-2xl border overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b text-sm text-gray-500 uppercase">
                <tr>
                    <th class="p-4">#</th>
                    <th class="p-4">Client</th>
                    <th class="p-4">Total</th>
                    <th class="p-4">Statut</th>
                    <th class="p-4">Date</th>
                    <th class="p-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="p-4 font-semibold text-gray-600">#{{ $order->id }}</td>
                        <td class="p-4">{{ $order->user?->name ?? 'Client supprimé' }}</td>
                        <td class="p-4 font-semibold text-orange-600">{{ $order->formatted_total }}</td>
                        <td class="p-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $order->status_badge }}
                                {{ $order->status === 'en_preparation' ? 'animate-pulse' : '' }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="p-4 text-gray-500 text-sm">{{ $order->created_at->format('d M Y') }}</td>
                        <td class="p-4 text-right">
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="text-orange-600 font-medium hover:underline text-sm">
                                Voir →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-10 text-center text-gray-400">Aucune commande trouvée</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-6">{{ $orders->links() }}</div>
    </div>

</div>
@endsection
