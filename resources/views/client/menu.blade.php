@extends('layouts.menu')

@section('content')
    <div x-data="menuComponent()" class="pt-28 px-6 pb-16">

        <div class="max-w-7xl mx-auto">

            <h1 class="text-3xl font-bold mb-10">
                Notre Menu 🍽️
            </h1>

            {{-- NOTIFICATION --}}
            <div x-show="showToast" x-transition
                class="fixed top-24 right-6 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg">
                Produit ajouté au panier ✅
            </div>

            <div class="max-w-7xl mx-auto px-6">

                  @foreach($categories as $category)
                <div class="mb-16">
                    <h3 class="text-2xl font-semibold mb-8 text-orange-600">{{ $category->name }}</h3>

                    <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                        @foreach($category->products as $product)
                            <div class="bg-gray-50 rounded-2xl shadow hover:shadow-lg transition overflow-hidden">
                                <img src="{{ $product->image ?? 'https://via.placeholder.com/300' }}"
                                     alt="{{ $product->name }}"
                                     class="h-40 w-full object-cover">
                                <div class="p-5">
                                    <h4 class="font-semibold text-lg">{{ $product->name }}</h4>
                                    <p class="text-sm text-gray-500 mt-1 line-clamp-2">
                                        {{ $product->description }}
                                    </p>
                                    <div class="mt-4 flex items-center justify-between">
                                        <span class="font-bold text-orange-600">
                                            {{ number_format($product->price, 0, ',', ' ') }} FCFA
                                        </span>

                                        <button @click="addToCart({{ $product->id }})"
                                            class="w-full bg-orange-500 text-white py-3 rounded-xl hover:bg-orange-600 transition transform hover:scale-105">
                                            Ajouter au panier
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            </div>

        </div>

    </div>
@endsection
