@extends('layouts.client')

@section('title', 'Mon espace')

@section('content')
<div class="min-h-screen bg-gray-100 py-10 px-6">
    <div class="max-w-7xl mx-auto">

        {{-- HEADER --}}
        <div class="mb-10">
            <h1 class="text-3xl font-bold text-gray-800">
                Bonjour {{ auth()->user()->name }} 👋
            </h1>
            <p class="text-gray-500 mt-2">Voici un aperçu de votre activité.</p>
        </div>

        {{-- STATS CARDS --}}
        <div class="grid md:grid-cols-3 gap-8 mb-12">

            <div class="bg-white p-8 rounded-3xl shadow hover:shadow-xl transition border-l-4 border-orange-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Total commandes</p>
                        <p class="text-4xl font-bold text-gray-800 mt-2">{{ $stats['total_orders'] }}</p>
                    </div>
                    <span class="text-4xl">📦</span>
                </div>
            </div>

            {{-- ✅ Utilise $stats['pending_orders'] du contrôleur
                 ❌ SUPPRIMÉ : $orders->where('status','en_preparation') qui ne compte
                    que les 5 dernières commandes chargées, pas toutes --}}
            <div class="bg-white p-8 rounded-3xl shadow hover:shadow-xl transition border-l-4 border-yellow-400">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">En attente</p>
                        <p class="text-4xl font-bold text-gray-800 mt-2">{{ $stats['pending_orders'] }}</p>
                    </div>
                    <span class="text-4xl">⏳</span>
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow hover:shadow-xl transition border-l-4 border-green-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Total dépensé</p>
                        <p class="text-4xl font-bold text-gray-800 mt-2">
                            {{ number_format($stats['total_spent'], 0, ',', ' ') }} FCFA
                        </p>
                    </div>
                    <span class="text-4xl">💰</span>
                </div>
            </div>

        </div>

        {{-- DERNIÈRES COMMANDES --}}
        <div class="bg-white rounded-3xl shadow p-8">

            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold">Dernières commandes</h2>
                <a href="{{ route('client.orders') }}" class="text-orange-500 hover:underline text-sm font-medium">
                    Voir tout →
                </a>
            </div>

            {{-- ✅ $orders est déjà limité à 5 dans le contrôleur
                 ❌ SUPPRIMÉ : ->take(5) en vue sur une collection déjà limitée (trompeur) --}}
            @forelse($orders as $order)
                <div class="flex justify-between items-center py-4 border-b last:border-0 px-2 hover:bg-gray-50 rounded-lg transition">
                    <div>
                        <p class="font-semibold text-gray-800">Commande #{{ $order->id }}</p>
                        {{-- ✅ Balise <p> imbriquée dans <p> supprimée (HTML invalide) --}}
                        <p class="text-sm text-gray-500 mt-0.5">
                            {{ $order->created_at?->format('d M Y à H:i') }}
                        </p>
                    </div>

                    <div class="flex items-center gap-6">
                        {{-- ✅ status_badge et status_label via accessors modernes du modèle --}}
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $order->status_badge }}
                            {{ $order->status === 'en_preparation' ? 'animate-pulse' : '' }}">
                            {{ $order->status_label }}
                        </span>

                        {{-- ✅ formatted_total accessor — inclut devise FCFA --}}
                        <span class="font-bold text-orange-600">
                            {{ $order->formatted_total }}
                        </span>

                        <a href="{{ route('client.orders.show', $order) }}"
                           class="text-sm text-gray-400 hover:text-orange-500 transition">
                            Détails →
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-10">
                    <p class="text-gray-400 mb-4">Aucune commande pour le moment.</p>
                    <a href="{{ route('menu') }}"
                       class="bg-orange-500 text-white px-6 py-2 rounded-xl hover:bg-orange-600 transition text-sm font-semibold">
                        Passer ma première commande
                    </a>
                </div>
            @endforelse

        </div>

        {{-- CTA --}}
        <div class="mt-10 text-center">
            <a href="{{ route('menu') }}"
               class="inline-block bg-orange-500 text-white px-8 py-4 rounded-2xl shadow hover:bg-orange-600 transition text-lg font-semibold">
                Commander maintenant 🛒
            </a>
        </div>

    </div>
</div>
@endsection
