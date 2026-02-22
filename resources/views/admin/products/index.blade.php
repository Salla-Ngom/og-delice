@extends('layouts.admin')

@section('content')

<div class="space-y-6">

    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Gestion des Produits</h1>

        <a href="{{ route('admin.products.create') }}"
           class="bg-orange-500 text-white px-4 py-2 rounded-xl shadow hover:bg-orange-600 transition">
            + Ajouter
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <table class="w-full text-left">
            <thead class="bg-gray-100 text-gray-600 text-sm">
                <tr>
                    <th class="p-4">Image</th>
                    <th class="p-4">Nom</th>
                    <th class="p-4">Prix</th>
                    <th class="p-4">Statut</th>
                    <th class="p-4">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($products as $product)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-4">
                            @if($product->image)
                                <img src="{{ asset('storage/'.$product->image) }}"
                                     class="w-14 h-14 object-cover rounded-lg">
                            @endif
                        </td>

                        <td class="p-4 font-medium">
                            {{ $product->name }}
                        </td>

                        <td class="p-4">
                            {{ number_format($product->price,0,',',' ') }} FCFA
                        </td>

                        <td class="p-4">
                            @if($product->is_active)
                                <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-xs">
                                    Actif
                                </span>
                            @else
                                <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs">
                                    Inactif
                                </span>
                            @endif
                        </td>

                        <td class="p-4 flex gap-2">
                            <a href="{{ route('admin.products.edit',$product) }}"
                               class="text-blue-600 hover:underline">Modifier</a>

                            <form action="{{ route('admin.products.destroy',$product) }}"
                                  method="POST">
                                @csrf
                                @method('DELETE')

                                <button class="text-red-600 hover:underline"
                                        onclick="return confirm('Supprimer ?')">
                                    Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>

    </div>

    {{ $products->links() }}

</div>

@endsection
