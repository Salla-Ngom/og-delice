@extends('layouts.client')

@section('content')

<div class="max-w-7xl mx-auto py-16 px-6">

    <h1 class="text-3xl font-bold mb-10 text-gray-800">
        🛒 Votre Panier
    </h1>

    @if(count($cart) > 0)

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

            @foreach($cart as $id => $item)

                <div class="flex items-center justify-between p-6 border-b">

                    {{-- Produit --}}
                    <div class="flex items-center gap-6">

                        <img src="{{ $item['image'] ?? 'https://via.placeholder.com/80' }}"
                             class="w-20 h-20 object-cover rounded-lg shadow">

                        <div>
                            <h3 class="font-semibold text-lg">
                                {{ $item['name'] }}
                            </h3>

                            <p class="text-gray-500">
                                {{ number_format($item['price'], 0, ',', ' ') }} FCFA
                            </p>
                        </div>

                    </div>

                    {{-- Quantité --}}
                    <form action="{{ route('cart.update', $id) }}" method="POST" class="flex items-center gap-3">
                        @csrf
                        <input type="number"
                               name="quantity"
                               value="{{ $item['quantity'] }}"
                               min="1"
                               class="w-16 border rounded-lg px-2 py-1 text-center">

                        <button class="bg-gray-200 px-3 py-1 rounded-lg hover:bg-gray-300">
                            ✔
                        </button>
                    </form>

                    {{-- Prix total produit --}}
                    <div class="font-bold text-orange-600 text-lg">
                        {{ number_format($item['price'] * $item['quantity'], 0, ',', ' ') }} FCFA
                    </div>

                    {{-- Supprimer --}}
                    <form action="{{ route('cart.remove', $id) }}" method="POST">
                        @csrf
                        <button class="text-red-600 hover:text-red-800 font-semibold">
                            Supprimer
                        </button>
                    </form>

                </div>

            @endforeach

        </div>

        {{-- Total --}}
        <div class="mt-10 flex justify-between items-center">

            <div class="text-2xl font-bold">
                Total :
                <span class="text-green-600">
                    {{ number_format($total, 0, ',', ' ') }} FCFA
                </span>
            </div>

            <a href="{{ route('checkout') }}"
               class="bg-orange-500 text-white px-8 py-4 rounded-xl shadow-lg hover:bg-orange-600 transition">
                Passer la commande
            </a>

        </div>

    @else

        <div class="bg-white p-12 rounded-2xl shadow text-center">
            <p class="text-gray-500 text-lg">
                Votre panier est vide 😔
            </p>

            <a href="{{ route('menu') }}"
               class="mt-6 inline-block bg-orange-500 text-white px-6 py-3 rounded-lg">
                Voir le menu
            </a>
        </div>

    @endif

</div>

@endsection
