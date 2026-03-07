@extends('layouts.admin')

@section('title', 'Produits')

@section('content')
<div class="space-y-6">

    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">Gestion des Produits</h1>
        <a href="{{ route('admin.products.create') }}"
           class="bg-orange-500 text-white px-5 py-2 rounded-xl shadow hover:bg-orange-600 transition font-semibold">
            + Ajouter un produit
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow overflow-hidden border">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-gray-500 text-sm uppercase border-b">
                <tr>
                    <th class="p-4">Image</th>
                    <th class="p-4">Nom</th>
                    <th class="p-4">Catégorie</th>
                    <th class="p-4">Prix</th>
                    <th class="p-4">Stock</th>
                    <th class="p-4">Statut</th>
                    <th class="p-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr class="border-t hover:bg-gray-50 transition">

                        <td class="p-4">
                            {{-- ✅ image_url accessor — gère le fallback --}}
                            <img src="{{ $product->image_url }}"
                                 class="w-14 h-14 object-cover rounded-lg shadow"
                                 alt="{{ $product->name }}">
                        </td>

                        <td class="p-4 font-medium text-gray-800">{{ $product->name }}</td>

                        <td class="p-4 text-sm text-gray-500">
                            {{ $product->category?->name ?? '—' }}
                        </td>

                        <td class="p-4">
                            @if($product->is_on_sale)
                                <span class="text-xs text-gray-400 line-through block">
                                    {{ number_format($product->price, 0, ',', ' ') }}
                                </span>
                                <span class="font-semibold text-orange-600">
                                    {{ $product->formatted_price }}
                                </span>
                            @else
                                <span class="font-semibold">{{ $product->formatted_price }}</span>
                            @endif
                        </td>

                        {{-- ✅ Alerte visuelle stock faible --}}
                        <td class="p-4">
                            <span class="{{ $product->stock <= 5 ? 'text-red-600 font-semibold' : 'text-gray-700' }}">
                                {{ $product->stock }}
                                @if($product->stock <= 5 && $product->stock > 0)
                                    <span class="text-xs ml-1">⚠️</span>
                                @elseif($product->stock === 0)
                                    <span class="text-xs text-red-500 ml-1">(rupture)</span>
                                @endif
                            </span>
                        </td>

                        <td class="p-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $product->is_active ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                {{ $product->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>

                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.products.edit', $product) }}"
                                   class="text-blue-600 hover:underline text-sm font-medium">
                                    Modifier
                                </a>

                                <form action="{{ route('admin.products.destroy', $product) }}"
                                      method="POST"
                                      onsubmit="return confirm('Supprimer {{ addslashes($product->name) }} ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline text-sm font-medium">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $products->links() }}

</div>
@endsection
