@extends('layouts.client')

@section('title', 'Commande #' . $order->id)

@section('content')
<div class="max-w-4xl mx-auto py-12 px-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-start mb-10">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Commande #{{ $order->id }}</h1>
            <p class="text-gray-500 mt-2">Passée le {{ $order->created_at?->format('d M Y à H:i') }}</p>
        </div>
        <span class="px-4 py-2 rounded-full text-sm font-semibold
            {{ $order->status_badge }}
            {{ $order->status === 'en_preparation' ? 'animate-pulse' : '' }}">
            {{ $order->status_label }}
        </span>
    </div>

    {{-- PROGRESSION --}}
    @if($order->status !== 'annulee')
        <div class="bg-white shadow-lg rounded-2xl p-8 mb-10 border">
            <h2 class="text-lg font-semibold mb-8">Suivi de la commande</h2>

            @php
                $steps = [
                    ['label' => 'En attente',     'key' => 'en_attente'],
                    ['label' => 'En préparation',  'key' => 'en_preparation'],
                    ['label' => 'Prête',           'key' => 'prete'],
                ];
                $statusOrder = ['en_attente' => 1, 'en_preparation' => 2, 'prete' => 3];
                $currentStep = $statusOrder[$order->status] ?? 1;
            @endphp

            <div class="flex items-start justify-between relative">
                {{-- Ligne de progression en arrière-plan --}}
                <div class="absolute top-5 left-0 right-0 h-1 bg-gray-200 z-0">
                    <div class="h-full bg-orange-500 transition-all duration-500"
                         style="width: {{ ($currentStep - 1) * 50 }}%"></div>
                </div>

                @foreach($steps as $index => $step)
                    @php $stepNumber = $index + 1; $reached = $currentStep >= $stepNumber; @endphp
                    <div class="flex flex-col items-center z-10 flex-1">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full border-2 transition
                            {{ $reached
                                ? 'bg-orange-500 border-orange-500 text-white'
                                : 'bg-white border-gray-300 text-gray-400' }}">
                            @if($reached)
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            @else
                                {{ $stepNumber }}
                            @endif
                        </div>
                        <span class="text-xs mt-2 text-center font-medium
                            {{ $reached ? 'text-orange-600' : 'text-gray-400' }}">
                            {{ $step['label'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="bg-red-50 border border-red-200 rounded-2xl p-6 mb-10 text-red-700 font-medium text-center">
            Cette commande a été annulée.
        </div>
    @endif

    {{-- ARTICLES --}}
    <div class="bg-white shadow-lg rounded-2xl p-8 border">
        <h2 class="text-lg font-semibold mb-6">Détails des articles</h2>

        <div class="space-y-4">
            @foreach($order->items as $item)
                <div class="flex items-center justify-between border-b pb-4 last:border-0 last:pb-0">

                    <div class="flex items-center gap-4">
                        {{-- ✅ image_url accessor — gère fallback si image null
                             ❌ SUPPRIMÉ : asset('storage/'.$item->product->image) sans vérification nullité --}}
                        <img src="{{ $item->product->image_url ?? asset('images/default-product.png') }}"
                             alt="{{ $item->product->name ?? 'Produit' }}"
                             class="w-16 h-16 object-cover rounded-xl shadow">

                        <div>
                            <p class="font-semibold text-gray-800">
                                {{ $item->product->name ?? 'Produit supprimé' }}
                            </p>
                            <p class="text-sm text-gray-500">Quantité : {{ $item->quantity }}</p>

                            {{-- ✅ Affiche le prix snapshot — pas le prix actuel du produit
                                 ❌ SUPPRIMÉ : $item->price (ancienne colonne) → $item->unit_price --}}
                            <p class="text-xs text-gray-400 mt-0.5">
                                @if($item->had_promo)
                                    <span class="line-through mr-1">{{ number_format($item->unit_price, 0, ',', ' ') }}</span>
                                    <span class="text-orange-500">{{ number_format($item->unit_price_promo, 0, ',', ' ') }} FCFA/u</span>
                                @else
                                    {{ number_format($item->unit_price, 0, ',', ' ') }} FCFA/u
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- ✅ subtotal accessor = effective_price * quantity --}}
                    <p class="font-semibold text-orange-600 whitespace-nowrap">
                        {{ number_format($item->subtotal, 0, ',', ' ') }} FCFA
                    </p>

                </div>
            @endforeach
        </div>

        {{-- TOTAL --}}
        <div class="mt-8 border-t pt-6 flex justify-between items-center">
            <span class="text-lg font-semibold text-gray-700">Total payé</span>
            {{-- ✅ formatted_total accessor --}}
            <span class="text-2xl font-bold text-orange-600">{{ $order->formatted_total }}</span>
        </div>
    </div>

    {{-- RETOUR --}}
    <div class="mt-10">
        <a href="{{ route('client.orders') }}"
           class="inline-block px-6 py-3 bg-gray-900 text-white rounded-xl shadow hover:bg-gray-800 transition font-medium">
            ← Retour à mes commandes
        </a>
    </div>

</div>
@endsection
