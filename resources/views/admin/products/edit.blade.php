@extends('layouts.admin')

@section('title', 'Modifier ' . $product->name)

@section('content')
<div class="max-w-5xl mx-auto">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Modifier le produit</h1>
            <p class="text-gray-500 mt-1">{{ $product->name }}</p>
        </div>
        <a href="{{ route('admin.products.index') }}"
           class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg transition text-sm">
            ← Retour
        </a>
    </div>

    <div class="bg-white shadow-xl rounded-2xl p-8 border">

        <form method="POST"
              action="{{ route('admin.products.update', $product) }}"
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid md:grid-cols-2 gap-6">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nom du produit</label>
                    <input type="text" name="name"
                           value="{{ old('name', $product->name) }}"
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-500 focus:outline-none @error('name') border-red-400 @enderror"
                           required>
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Catégorie</label>
                    <select name="category_id"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-500 focus:outline-none"
                            required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Prix (FCFA)</label>
                    <input type="number" step="1" name="price"
                           value="{{ old('price', $product->price) }}"
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-500 focus:outline-none @error('price') border-red-400 @enderror"
                           required>
                    @error('price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Prix promo (optionnel)</label>
                    <input type="number" step="1" name="discount_price"
                           value="{{ old('discount_price', $product->discount_price) }}"
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    <p class="text-xs text-gray-400 mt-1">Doit être inférieur au prix normal</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Stock</label>
                    <input type="number" name="stock"
                           value="{{ old('stock', $product->stock) }}"
                           min="0"
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-500 focus:outline-none"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Type de service</label>
                    <select name="service_type"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-500 focus:outline-none">
                        <option value="">— Aucun —</option>
                        @foreach(App\Models\Product::SERVICE_TYPES as $val => $label)
                            <option value="{{ $val }}"
                                {{ old('service_type', $product->service_type) === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="4"
                          class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-500 focus:outline-none">{{ old('description', $product->description) }}</textarea>
            </div>

            {{-- IMAGE ACTUELLE --}}
            @if($product->image)
                <div>
                    <p class="text-sm font-semibold text-gray-700 mb-2">Image actuelle</p>
                    {{-- ✅ image_url accessor --}}
                    <img src="{{ $product->image_url }}"
                         class="w-32 h-32 object-cover rounded-xl shadow border">
                </div>
            @endif

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    {{ $product->image ? 'Changer l\'image' : 'Ajouter une image' }}
                </label>
                <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp"
                       class="w-full border rounded-lg px-4 py-2 bg-white text-sm">
                <p class="text-xs text-gray-400 mt-1">JPG, PNG ou WEBP – max 2MB</p>
            </div>

            {{-- CHECKBOXES --}}
            <div class="flex flex-wrap gap-6">
                @foreach([
                    ['is_active',   'Produit actif',        $product->is_active],
                    ['is_featured', 'Mettre en avant',      $product->is_featured],
                    ['is_popular',  'Populaire',            $product->is_popular],
                    ['is_traiteur', 'Service traiteur',     $product->is_traiteur],
                ] as [$name, $label, $checked])
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="{{ $name }}" value="1"
                               {{ old($name, $checked) ? 'checked' : '' }}
                               class="w-5 h-5 text-orange-600 rounded">
                        <span class="text-sm text-gray-700">{{ $label }}</span>
                    </label>
                @endforeach
            </div>

            <div class="flex justify-end gap-4 pt-4 border-t">
                <a href="{{ route('admin.products.index') }}"
                   class="px-6 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition text-sm">
                    Annuler
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition font-semibold">
                    Enregistrer les modifications
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
