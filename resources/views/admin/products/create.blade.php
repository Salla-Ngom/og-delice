@extends('layouts.admin')

@section('content')

<div class="max-w-5xl mx-auto">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
            Ajouter un produit
        </h1>

        <a href="{{ route('admin.products.index') }}"
           class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg transition">
            ← Retour
        </a>
    </div>

    {{-- CARD --}}
    <div class="bg-white shadow-xl rounded-2xl p-8">

        {{-- ERREURS --}}
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST"
              action="{{ route('admin.products.store') }}"
              enctype="multipart/form-data"
              class="space-y-6">

            @csrf

            {{-- NOM --}}
            <div>
                <label class="block mb-2 font-semibold text-gray-700">
                    Nom du produit
                </label>
                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       required
                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:outline-none">
            </div>

            {{-- CATEGORIE --}}
            <div>
                <label class="block mb-2 font-semibold text-gray-700">
                    Catégorie
                </label>
                <select name="category_id"
                        required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    <option value="">-- Choisir une catégorie --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- DESCRIPTION --}}
            <div>
                <label class="block mb-2 font-semibold text-gray-700">
                    Description
                </label>
                <textarea name="description"
                          rows="4"
                          class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:outline-none">{{ old('description') }}</textarea>
            </div>

            {{-- PRIX --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="block mb-2 font-semibold text-gray-700">
                        Prix (FCFA)
                    </label>
                    <input type="number"
                           step="0.01"
                           name="price"
                           value="{{ old('price') }}"
                           required
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:outline-none">
                </div>

                <div>
                    <label class="block mb-2 font-semibold text-gray-700">
                        Prix promo (optionnel)
                    </label>
                    <input type="number"
                           step="0.01"
                           name="discount_price"
                           value="{{ old('discount_price') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:outline-none">
                </div>

            </div>

            {{-- STOCK --}}
            <div>
                <label class="block mb-2 font-semibold text-gray-700">
                    Stock disponible
                </label>
                <input type="number"
                       name="stock"
                       value="{{ old('stock',0) }}"
                       min="0"
                       required
                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:outline-none">
            </div>

            {{-- IMAGE --}}
            <div>
                <label class="block mb-2 font-semibold text-gray-700">
                    Image produit
                </label>
                <input type="file"
                       name="image"
                       accept="image/*"
                       class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-white">
                <p class="text-sm text-gray-500 mt-1">
                    JPG, PNG ou WEBP – max 2MB
                </p>
            </div>

            {{-- OPTIONS --}}
            <div class="flex items-center gap-8">

                <label class="flex items-center gap-2">
                    <input type="checkbox"
                           name="is_active"
                           value="1"
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-5 h-5 text-orange-600">
                    <span>Produit actif</span>
                </label>

                <label class="flex items-center gap-2">
                    <input type="checkbox"
                           name="is_featured"
                           value="1"
                           {{ old('is_featured') ? 'checked' : '' }}
                           class="w-5 h-5 text-orange-600">
                    <span>Mettre en avant</span>
                </label>

            </div>

            {{-- BOUTON --}}
            <div class="pt-6">
                <button type="submit"
                        class="w-full bg-orange-600 hover:bg-orange-700 text-white py-3 rounded-xl font-semibold text-lg transition shadow-md">
                    Enregistrer le produit
                </button>
            </div>

        </form>

    </div>

</div>

@endsection
