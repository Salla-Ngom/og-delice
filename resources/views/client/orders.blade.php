@extends('layouts.client')

@section('content')

<div class="max-w-6xl mx-auto py-10 px-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-800">
            Mes Commandes
        </h1>
    </div>

    {{-- RESUME STATS --}}
    <div class="grid md:grid-cols-3 gap-6 mb-10">

        <div class="bg-white p-6 rounded-2xl shadow border">
            <p class="text-sm text-gray-500">Total commandes</p>
            <p class="text-2xl font-bold mt-2">
                {{ $orders->count() }}
            </p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow border">
            <p class="text-sm text-gray-500">En préparation</p>
            <p class="text-2xl font-bold text-blue-600 mt-2">
                {{ $orders->where('status','en_preparation')->count() }}
            </p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow border">
            <p class="text-sm text-gray-500">Total dépensé</p>
            <p class="text-2xl font-bold text-orange-600 mt-2">
                {{ number_format($orders->sum('total_price'),0,',',' ') }} FCFA
            </p>
        </div>

    </div>

    {{-- LISTE COMMANDES --}}
    @forelse($orders as $order)

        <div class="bg-white shadow-md hover:shadow-xl transition duration-300 
                    rounded-2xl p-6 mb-6 border">

            <div class="flex justify-between items-start mb-4">

                <div>
                    <p class="font-semibold text-gray-800">
                        Commande #{{ $order->id }}
                    </p>

                    <p class="text-sm text-gray-500 mt-1">
                        {{ $order->created_at?->format('d M Y à H:i') }}
                    </p>

                    <p class="mt-2 text-sm text-gray-400">
                        {{ $order->items->count() }} produit(s)
                    </p>
                </div>

                <span class="px-4 py-1.5 rounded-full text-xs font-semibold
                    {{ $order->status_badge }}
                    {{ $order->status == 'en_preparation' ? 'animate-pulse' : '' }}">
                    {{ $order->status_label }}
                </span>

            </div>

            <div class="flex justify-between items-center">

                <p class="font-bold text-orange-600 text-lg">
                    {{ number_format($order->total_price,0,',',' ') }} FCFA
                </p>

                <a href="{{ route('client.orders.show', $order) }}"
                   class="text-sm text-gray-600 hover:text-orange-600 transition font-medium">
                    Voir détails →
                </a>

            </div>

        </div>

    @empty

        <div class="bg-white shadow rounded-2xl p-10 text-center border">
            <p class="text-gray-500 mb-4">
                Vous n'avez pas encore passé de commande.
            </p>

            <a href="{{ route('menu') }}"
               class="bg-orange-600 text-white px-6 py-2 rounded-lg hover:bg-orange-700 transition">
                Voir le menu
            </a>
        </div>

    @endforelse

</div>

@endsection