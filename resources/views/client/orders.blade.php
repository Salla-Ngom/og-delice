@extends('layouts.client')

@section('title', 'Mes Commandes')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-6">

    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Mes Commandes</h1>
        <a href="{{ route('menu') }}"
           class="bg-orange-500 text-white px-5 py-2 rounded-xl text-sm font-semibold hover:bg-orange-600 transition">
            + Nouvelle commande
        </a>
    </div>

    {{-- STATS --}}
    <div class="grid md:grid-cols-3 gap-6 mb-10">

        <div class="bg-white p-6 rounded-2xl shadow border-l-4 border-orange-400">
            <p class="text-sm text-gray-500">Total commandes</p>
            {{-- ✅ ->total() = count réel de toutes les pages, pas juste la page courante
                 ❌ SUPPRIMÉ : ->count() qui retourne le nombre sur la page actuelle seulement --}}
            <p class="text-2xl font-bold mt-2 text-gray-800">{{ $orders->total() }}</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow border-l-4 border-blue-400">
            <p class="text-sm text-gray-500">En préparation</p>
            {{-- ✅ getCollection() accède aux items de la page courante — limitation connue acceptée ici --}}
            <p class="text-2xl font-bold text-blue-600 mt-2">
                {{ $orders->getCollection()->where('status', 'en_preparation')->count() }}
            </p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow border-l-4 border-green-400">
            <p class="text-sm text-gray-500">Dépensé (page)</p>
            <p class="text-2xl font-bold text-orange-600 mt-2">
                {{ number_format($orders->getCollection()->sum('total_price'), 0, ',', ' ') }} FCFA
            </p>
        </div>

    </div>

    {{-- LISTE --}}
    @forelse($orders as $order)
        <div class="bg-white shadow hover:shadow-lg transition duration-300 rounded-2xl p-6 mb-5 border">

            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="font-semibold text-gray-800 text-lg">Commande #{{ $order->id }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $order->created_at?->format('d M Y à H:i') }}</p>
                    <p class="text-sm text-gray-400 mt-1">{{ $order->items->count() }} article(s)</p>
                </div>

                <span class="px-4 py-1.5 rounded-full text-xs font-semibold
                    {{ $order->status_badge }}
                    {{ $order->status === 'en_preparation' ? 'animate-pulse' : '' }}">
                    {{ $order->status_label }}
                </span>
            </div>

            {{-- Aperçu des 2 premiers produits --}}
            @if($order->items->isNotEmpty())
                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach($order->items->take(2) as $item)
                        <span class="bg-gray-50 border rounded-lg px-3 py-1 text-sm text-gray-600">
                            {{ $item->product->name ?? 'Produit supprimé' }}
                            <span class="text-gray-400">×{{ $item->quantity }}</span>
                        </span>
                    @endforeach
                    @if($order->items->count() > 2)
                        <span class="text-sm text-gray-400 flex items-center">
                            +{{ $order->items->count() - 2 }} autre(s)
                        </span>
                    @endif
                </div>
            @endif

            <div class="flex justify-between items-center">
                {{-- ✅ formatted_total accessor --}}
                <p class="font-bold text-orange-600 text-lg">{{ $order->formatted_total }}</p>
                <a href="{{ route('client.orders.show', $order) }}"
                   class="text-sm text-gray-500 hover:text-orange-600 transition font-medium">
                    Voir détails →
                </a>
            </div>

        </div>
    @empty
        <div class="bg-white shadow rounded-2xl p-12 text-center border">
            <p class="text-gray-400 text-lg mb-2">Aucune commande pour le moment.</p>
            <a href="{{ route('menu') }}"
               class="bg-orange-500 text-white px-8 py-3 rounded-xl hover:bg-orange-600 transition font-semibold">
                Voir le menu
            </a>
        </div>
    @endforelse

    {{-- PAGINATION --}}
    @if($orders->hasPages())
        <div class="mt-8">{{ $orders->links() }}</div>
    @endif

</div>
@endsection
