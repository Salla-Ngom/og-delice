@extends('layouts.admin')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Commandes
            </h1>
            <p class="text-gray-500">
                Gestion des commandes clients
            </p>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white shadow-xl rounded-2xl border overflow-hidden">

        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b">
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

                        <td class="p-4 font-semibold">
                            #{{ $order->id }}
                        </td>

                        <td class="p-4">
                            {{ $order->user->name ?? 'Utilisateur supprimé' }}
                        </td>

                        <td class="p-4 font-semibold text-orange-600">
                            {{ number_format($order->total_price,0,',',' ') }} FCFA
                        </td>

                        <td class="p-4">
                            <span class="px-3 py-1 rounded-full text-xs
                                @if($order->status == 'pending') bg-yellow-100 text-yellow-700
                                @elseif($order->status == 'completed') bg-green-100 text-green-700
                                @else bg-red-100 text-red-700
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>

                        <td class="p-4 text-gray-500 text-sm">
                            {{ $order->created_at->format('d M Y') }}
                        </td>

                        <td class="p-4 text-right">
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="text-orange-600 font-medium hover:underline">
                                Voir
                            </a>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-gray-400">
                            Aucune commande trouvée
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>

        <div class="p-6">
            {{ $orders->links() }}
        </div>

    </div>

</div>

@endsection
