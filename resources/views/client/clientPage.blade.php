@extends('layouts.client')

@section('content')
    <div class="min-h-screen bg-gray-100 py-10 px-6">

        <div class="max-w-7xl mx-auto">

            {{-- HEADER --}}
            <div class="mb-10">
                <h1 class="text-3xl font-bold text-gray-800">
                    Bonjour {{ auth()->user()->name }} 👋
                </h1>
                <p class="text-gray-500 mt-2">
                    Voici un aperçu de votre activité.
                </p>
            </div>

            {{-- STATS CARDS --}}
            <div class="grid md:grid-cols-3 gap-8 mb-12">

                {{-- Total commandes --}}
                <div class="bg-white p-8 rounded-3xl shadow hover:shadow-xl transition border-l-4 border-orange-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-gray-500">Total commandes</h3>
                            <p class="text-4xl font-bold text-gray-800 mt-2">
                                {{ $stats['total_orders'] }}
                            </p>
                        </div>
                        <div class="text-4xl text-orange-500">
                            📦
                        </div>
                    </div>
                </div>

                {{-- En cours --}}
                <div class="bg-white p-8 rounded-3xl shadow hover:shadow-xl transition border-l-4 border-yellow-400">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-gray-500">Commandes en cours</h3>
                            <p class="text-4xl font-bold text-gray-800 mt-2">
                               {{ $orders->where('status','en_preparation')->count() }}
                            </p>
                        </div>
                        <div class="text-4xl text-yellow-400">
                            ⏳
                        </div>
                    </div>
                </div>

                {{-- Total dépensé --}}
                <div class="bg-white p-8 rounded-3xl shadow hover:shadow-xl transition border-l-4 border-green-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-gray-500">Total dépensé</h3>
                            <p class="text-4xl font-bold text-gray-800 mt-2">
                                {{ number_format($stats['total_spent'], 0, ',', ' ') }} FCFA
                            </p>
                        </div>
                        <div class="text-4xl text-green-500">
                            💰
                        </div>
                    </div>
                </div>

            </div>

            {{-- DERNIERES COMMANDES --}}
            <div class="bg-white rounded-3xl shadow p-8">

                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold">Dernières commandes</h2>

                    <a href="{{ route('client.orders') }}" class="text-orange-500 hover:underline text-sm">
                        Voir tout
                    </a>
                </div>

                @forelse($orders->take(5) as $order)
                    <div class="flex justify-between items-center py-4 border-b last:border-0">

                        <div>
                            <p class="font-semibold">
                                Commande #{{ $order->id }}
                            </p>

                            <p class="text-sm text-gray-500">
                            <p class="text-sm text-gray-500">
                                {{ $order->created_at?->format('d M Y à H:i') }}
                            </p>
                            </p>
                        </div>

                        <div class="flex items-center gap-6">

                            {{-- Status Badge --}}
                         <span class="px-3 py-1 rounded-full text-xs font-semibold
    {{ $order->status_badge }}
    {{ $order->status == 'en_preparation' ? 'animate-pulse' : '' }}">
    {{ $order->status_label }}
</span>

                            <span class="font-bold text-orange-600">
                                {{ number_format($order->total_price, 0, ',', ' ') }} FCFA
                            </span>

                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-6">
                        Aucune commande pour le moment.
                    </p>
                @endforelse

            </div>

            {{-- CTA --}}
            <div class="mt-10 text-center">
                <a href="{{ route('menu')}}"
                    class="bg-orange-500 text-white px-8 py-4 rounded-2xl shadow hover:bg-orange-600 transition text-lg">
                    Commander maintenant
                </a>
            </div>

        </div>

    </div>
@endsection
