@extends('layouts.client')

@section('title', 'Mon Panier')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 md:px-6">

    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Mon Panier 🛒</h1>
        <a href="{{ route('menu') }}" class="text-sm text-gray-500 hover:text-orange-500 transition">
            ← Continuer mes achats
        </a>
    </div>

    @php $cart = session('cart', []); @endphp

    @if(empty($cart))
        <div class="bg-white rounded-3xl shadow p-12 text-center border">
            <p class="text-5xl mb-4">🛒</p>
            <p class="text-gray-400 text-lg mb-6">Votre panier est vide.</p>
            <a href="{{ route('menu') }}"
               class="bg-orange-500 text-white px-8 py-3 rounded-xl hover:bg-orange-600 transition font-semibold">
                Voir le menu
            </a>
        </div>

    @else
        <div class="grid md:grid-cols-3 gap-6">

            {{-- LISTE DES PRODUITS --}}
            <div class="md:col-span-2 space-y-3">

                @foreach($cart as $id => $details)
                    <div class="bg-white rounded-2xl shadow border p-4">

                        {{-- LIGNE PRINCIPALE : image + infos + supprimer --}}
                        <div class="flex items-center gap-3 mb-3">
                            <img src="{{ $details['image'] ?? asset('images/default-product.png') }}"
                                 alt="{{ $details['name'] ?? 'Produit' }}"
                                 class="w-16 h-16 object-cover rounded-xl shadow shrink-0">

                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-800 truncate text-sm md:text-base">
                                    {{ $details['name'] }}
                                </p>
                                <p class="text-orange-600 font-bold text-sm mt-0.5">
                                    {{ number_format($details['price'], 0, ',', ' ') }} FCFA
                                </p>
                            </div>

                            {{-- Bouton supprimer --}}
                            <form action="{{ route('cart.remove', $id) }}" method="POST" class="shrink-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-8 h-8 flex items-center justify-center text-gray-300 hover:text-red-500 transition rounded-lg hover:bg-red-50"
                                        title="Supprimer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>

                        {{-- LIGNE SECONDAIRE : quantité + sous-total --}}
                        <div class="flex items-center justify-between pl-0 md:pl-20">

                            {{-- Boutons quantité --}}
                            <form action="{{ route('cart.update', $id) }}" method="POST"
                                  class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <button type="button" onclick="stepQty(this, -1)"
                                        class="w-8 h-8 rounded-full border text-gray-600 hover:border-orange-500 hover:text-orange-500 transition font-bold text-lg flex items-center justify-center">
                                    −
                                </button>
                                <input type="number" name="quantity"
                                       value="{{ $details['quantity'] }}" min="1" max="99"
                                       class="w-12 text-center border rounded-lg px-1 py-1.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none"
                                       onchange="this.form.submit()">
                                <button type="button" onclick="stepQty(this, 1)"
                                        class="w-8 h-8 rounded-full border text-gray-600 hover:border-orange-500 hover:text-orange-500 transition font-bold text-lg flex items-center justify-center">
                                    +
                                </button>
                            </form>

                            {{-- Sous-total --}}
                            <p class="font-bold text-gray-800 text-sm md:text-base">
                                {{ number_format($details['price'] * $details['quantity'], 0, ',', ' ') }} FCFA
                            </p>
                        </div>

                    </div>
                @endforeach
            </div>

            {{-- RÉCAPITULATIF --}}
            <div>
                <div class="bg-white rounded-2xl shadow border p-5 md:sticky md:top-24">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Récapitulatif</h2>

                    @php
                        $total     = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
                        $itemCount = collect($cart)->sum('quantity');
                    @endphp

                    <div class="space-y-3 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Articles</span>
                            <span>{{ $itemCount }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Sous-total</span>
                            <span>{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="flex justify-between text-xs text-gray-400">
                            <span>Livraison</span>
                            <span>À confirmer</span>
                        </div>
                    </div>

                    <div class="border-t mt-4 pt-4 flex justify-between font-bold text-gray-800">
                        <span>Total</span>
                        <span class="text-orange-600 text-lg">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                    </div>

                    @if(auth()->user()->has_delivery_address)
                        <div class="mt-4 bg-orange-50 rounded-xl p-3 text-xs text-gray-600 flex gap-2">
                            <span class="shrink-0">📍</span>
                            <span>{{ auth()->user()->delivery_address }}</span>
                        </div>
                    @else
                        <a href="{{ route('profile.edit') }}"
                           class="mt-3 flex items-center gap-1 text-xs text-orange-500 hover:underline">
                            + Ajouter une adresse de livraison
                        </a>
                    @endif

                    <form action="{{ route('checkout') }}" method="POST" class="mt-5">
                        @csrf
                        <button type="submit"
                                class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-4 rounded-xl shadow transition text-base">
                            Passer la commande →
                        </button>
                    </form>

                    <p class="text-xs text-gray-400 text-center mt-3">
                        Les prix seront confirmés à la validation
                    </p>
                </div>
            </div>

        </div>
    @endif

</div>

<script>
function stepQty(btn, delta) {
    const form  = btn.closest('form');
    const input = form.querySelector('input[name="quantity"]');
    const newVal = Math.max(1, Math.min(99, parseInt(input.value) + delta));
    if (newVal !== parseInt(input.value)) {
        input.value = newVal;
        form.submit();
    }
}
</script>

@endsection
