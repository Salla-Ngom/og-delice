@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-16 px-6">

    <h2 class="text-3xl font-bold mb-10">Votre Panier</h2>

    @if(session('cart'))
        <table class="w-full bg-white shadow rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-4 text-left">Produit</th>
                    <th class="p-4">Prix</th>
                    <th class="p-4">Quantité</th>
                    <th class="p-4">Total</th>
                    <th class="p-4">Action</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp

                @foreach(session('cart') as $id => $details)
                    @php $subtotal = $details['price'] * $details['quantity']; $total += $subtotal; @endphp

                    <tr class="border-b">
                        <td class="p-4 flex items-center gap-4">
                            <img src="{{ $details['image'] }}" class="w-16 h-16 object-cover rounded">
                            {{ $details['name'] }}
                        </td>
                        <td class="p-4">{{ number_format($details['price'],0,',',' ') }} FCFA</td>
                        <td class="p-4">
                            <form action="{{ route('cart.update', $id) }}" method="POST">
                                @csrf
                                <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1"
                                       class="w-16 border rounded px-2 py-1">
                                <button class="ml-2 text-blue-500">OK</button>
                            </form>
                        </td>
                        <td class="p-4 font-semibold">
                            {{ number_format($subtotal,0,',',' ') }} FCFA
                        </td>
                        <td class="p-4">
                            <a href="{{ route('cart.remove', $id) }}" class="text-red-500">
                                Supprimer
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-8 text-right">
            <h3 class="text-2xl font-bold">
                Total : {{ number_format($total,0,',',' ') }} FCFA
            </h3>

            <button class="mt-4 bg-orange-500 text-white px-8 py-3 rounded-lg hover:bg-orange-600">
                Passer la commande
            </button>
        </div>
    @else
        <p>Votre panier est vide.</p>
    @endif

</div>
@endsection
