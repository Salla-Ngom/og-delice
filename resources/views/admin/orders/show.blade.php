@extends('layouts.admin')

@section('content')

<div class="max-w-5xl mx-auto space-y-8">

    {{-- HEADER --}}
    <div>
        <h1 class="text-3xl font-bold text-gray-800">
            Commande #{{ $order->id }}
        </h1>
        <p class="text-gray-500 mt-2">
            Passée le {{ $order->created_at->format('d M Y à H:i') }}
        </p>
    </div>

    {{-- CLIENT INFO --}}
    <div class="bg-white shadow rounded-2xl p-6 border">
        <h2 class="text-lg font-semibold mb-4">Client</h2>

        <p><strong>Nom :</strong> {{ $order->user->name }}</p>
        <p><strong>Email :</strong> {{ $order->user->email }}</p>
    </div>

    {{-- ORDER ITEMS --}}
    <div class="bg-white shadow rounded-2xl p-6 border">

        <h2 class="text-lg font-semibold mb-6">Produits commandés</h2>

        <table class="w-full text-left">
            <thead class="border-b">
                <tr>
                    <th class="pb-3">Produit</th>
                    <th class="pb-3">Prix</th>
                    <th class="pb-3">Quantité</th>
                    <th class="pb-3">Total</th>
                </tr>
            </thead>

            <tbody>
                @foreach($order->items as $item)
                    <tr class="border-b">
                        <td class="py-4">
                            {{ $item->product->name ?? 'Produit supprimé' }}
                        </td>

                        <td>
                            {{ number_format($item->price,0,',',' ') }} FCFA
                        </td>

                        <td>
                            {{ $item->quantity }}
                        </td>

                        <td class="font-semibold text-orange-600">
                            {{ number_format($item->price * $item->quantity,0,',',' ') }} FCFA
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-right mt-6 text-xl font-bold">
            Total : {{ number_format($order->total_price,0,',',' ') }} FCFA
        </div>

    </div>

    {{-- STATUS UPDATE --}}
    <div class="bg-white shadow rounded-2xl p-6 border">

        <h2 class="text-lg font-semibold mb-4">
            Modifier le statut
        </h2>

        <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}">
            @csrf
            @method('PUT')

            <select name="status"
        class="border rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-500 focus:outline-none">

    <option value="en_attente"
        {{ $order->status == 'en_attente' ? 'selected' : '' }}>
        En attente
    </option>

    <option value="en_preparation"
        {{ $order->status == 'en_preparation' ? 'selected' : '' }}>
        En préparation
    </option>

    <option value="prete"
        {{ $order->status == 'prete' ? 'selected' : '' }}>
        Prête
    </option>

    <option value="annulee"
        {{ $order->status == 'annulee' ? 'selected' : '' }}>
        Annulée
    </option>

</select>

            <button type="submit"
                    class="ml-4 px-5 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition">
                Mettre à jour
            </button>

        </form>

    </div>

</div>

@endsection
