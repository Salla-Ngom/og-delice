@extends('layouts.admin')

@section('content')

<div class="max-w-5xl mx-auto">

    {{-- HEADER --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
            Modifier le produit
        </h1>
        <p class="text-gray-500 mt-2">
            Mise à jour des informations du produit
        </p>
    </div>

    {{-- MESSAGE SUCCESS --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    {{-- FORM --}}
    <div class="bg-white shadow-xl rounded-2xl p-8 border">

        <form method="POST"
              action="{{ route('admin.products.update', $product) }}"
              enctype="multipart/form-data"
              class="space-y-6">

            @csrf
            @method('PUT')

            {{-- NAME --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Nom du produit
                </label>
                <input type="text"
                       name="name"
                       value="{{ old('name', $product->name) }}"
                       class="w-full mt-2 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-500"
                       required>
            </div>

            {{-- CATEGORY --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Catégorie
                </label>
                <select name="category_id"
                        class="w-full mt-2 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-500"
                        required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ $product->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- PRICE --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Prix (FCFA)
                </label>
                <input type="number"
                       step="0.01"
                       name="price"
                       value="{{ old('price', $product->price) }}"
                       class="w-full mt-2 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-500"
                       required>
            </div>

            {{-- STOCK --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Stock
                </label>
                <input type="number"
                       name="stock"
                       value="{{ old('stock', $product->stock) }}"
                       class="w-full mt-2 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-500"
                       required>
            </div>

            {{-- DESCRIPTION --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Description
                </label>
                <textarea name="description"
                          rows="4"
                          class="w-full mt-2 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-500">{{ old('description', $product->description) }}</textarea>
            </div>

            {{-- IMAGE ACTUELLE --}}
            @if($product->image)
                <div>
                    <p class="text-sm text-gray-600 mb-2">Image actuelle :</p>
                    <img src="{{ asset('storage/' . $product->image) }}"
                         class="w-32 h-32 object-cover rounded-xl shadow">
                </div>
            @endif

            {{-- NOUVELLE IMAGE --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Changer l'image
                </label>
                <input type="file"
                       name="image"
                       class="w-full mt-2 border rounded-lg px-4 py-2">
            </div>

            {{-- ACTIVE --}}
            <div class="flex items-center gap-3">
                <input type="checkbox"
                       name="is_active"
                       value="1"
                       {{ $product->is_active ? 'checked' : '' }}
                       class="w-5 h-5 text-orange-600">
                <label class="text-gray-700">
                    Produit actif
                </label>
            </div>

            {{-- FEATURED --}}
            <div class="flex items-center gap-3">
                <input type="checkbox"
                       name="is_featured"
                       value="1"
                       {{ $product->is_featured ? 'checked' : '' }}
                       class="w-5 h-5 text-orange-600">
                <label class="text-gray-700">
                    Produit en vedette
                </label>
            </div>

            {{-- BUTTONS --}}
            <div class="flex justify-end gap-4 pt-6">

                <a href="{{ route('admin.products.index') }}"
                   class="px-6 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                    Annuler
                </a>

                <button type="submit"
                        class="px-6 py-2 bg-gradient-to-r from-orange-500 to-red-500
                               text-white rounded-lg shadow-lg hover:scale-105 transition">
                    Mettre à jour
                </button>

            </div>

        </form>

    </div>

</div>

@endsection
