@extends('layouts.client')

@section('title', 'Notre Menu')

@section('content')
<div class="pt-10 px-6 pb-16">
    <div class="max-w-7xl mx-auto">

        {{-- HEADER + FILTRES PAR CATÉGORIE --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-10">
            <h1 class="text-3xl font-bold text-gray-800">Notre Menu 🍽️</h1>

            {{-- ✅ Filtres via GET — remplace le Alpine menuComponent() qui ne faisait que du CSS côté client
                 sans réel filtrage en base. Maintenant ProductController filtre en DB via scopeByCategory() --}}
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('menu') }}"
                   class="px-4 py-2 rounded-full text-sm font-medium transition
                          {{ !request('category') ? 'bg-orange-500 text-white shadow' : 'bg-white text-gray-600 border hover:border-orange-400' }}">
                    Tout
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('menu', ['category' => $cat->id]) }}"
                       class="px-4 py-2 rounded-full text-sm font-medium transition
                              {{ request('category') == $cat->id ? 'bg-orange-500 text-white shadow' : 'bg-white text-gray-600 border hover:border-orange-400' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- TOAST — fourni par client.blade.php (layout), pas besoin de le redéfinir ici --}}

        {{-- GRILLE PRODUITS --}}
        {{-- ✅ $products est maintenant paginé depuis ProductController
             ❌ SUPPRIMÉ : @foreach($categories as $category) @foreach($category->products as $product)
                qui chargeait TOUS les produits sans pagination ni filtrage propre --}}
        @if($products->isEmpty())
            <div class="text-center py-20">
                <p class="text-gray-400 text-lg mb-4">Aucun produit dans cette catégorie.</p>
                <a href="{{ route('menu') }}" class="text-orange-500 hover:underline text-sm">
                    Voir tous les produits
                </a>
            </div>
        @else
            <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div class="bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden group">

                        <div class="relative overflow-hidden h-44">
                            {{-- ✅ image_url accessor — gère le fallback si image null
                                 ❌ SUPPRIMÉ : asset('storage/'.$product->image) qui plante si image est null --}}
                            <img src="{{ $product->image_url }}"
                                 alt="{{ $product->name }}"
                                 class="h-full w-full object-cover group-hover:scale-105 transition duration-300"
                                 loading="lazy">

                            @if($product->is_on_sale)
                                <span class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                    -{{ $product->discount_percent }}%
                                </span>
                            @endif

                            @if($product->preparation_time)
                                <span class="absolute bottom-2 right-2 bg-black/50 text-white text-xs px-2 py-0.5 rounded-full">
                                    {{ $product->preparation_time_formatted }}
                                </span>
                            @endif

                            @unless($product->is_available)
                                <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
                                    <span class="bg-black/60 text-white text-xs px-3 py-1 rounded-full font-semibold">
                                        Indisponible
                                    </span>
                                </div>
                            @endunless
                        </div>

                        <div class="p-4">
                            <p class="text-xs text-orange-400 font-medium uppercase tracking-wide">
                                {{ $product->category->name ?? '' }}
                            </p>
                            <h4 class="font-semibold text-gray-800 mt-1">{{ $product->name }}</h4>
                            <p class="text-sm text-gray-400 mt-1 line-clamp-2">{{ $product->description }}</p>

                            <div class="mt-4 flex items-center justify-between gap-2">
                                <div>
                                    @if($product->is_on_sale)
                                        <span class="text-xs text-gray-400 line-through block">
                                            {{ number_format($product->price, 0, ',', ' ') }} FCFA
                                        </span>
                                    @endif
                                    {{-- ✅ formatted_price inclut FCFA et utilise final_price --}}
                                    <span class="font-bold text-orange-600">{{ $product->formatted_price }}</span>
                                </div>

                                @if($product->is_available)
                                    {{-- ✅ addToCart() défini dans client.blade.php — disponible partout
                                         ❌ SUPPRIMÉ : @click="addToCart()" Alpine qui nécessitait un composant JS séparé --}}
                                    <button onclick="addToCart({{ $product->id }}, this)"
                                            class="bg-orange-500 text-white text-xs px-4 py-2 rounded-xl
                                                   hover:bg-orange-600 transition font-semibold whitespace-nowrap">
                                        + Ajouter
                                    </button>
                                @else
                                    <span class="text-xs text-gray-400 italic">Indisponible</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ✅ Pagination Laravel --}}
            <div class="mt-12">
                {{ $products->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
