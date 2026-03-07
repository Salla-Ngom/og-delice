@extends('layouts.admin')

@section('title', 'Commande #' . $order->id)

@section('content')
<div class="max-w-5xl mx-auto space-y-8">

    {{-- HEADER --}}
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Commande #{{ $order->id }}</h1>
            <p class="text-gray-500 mt-2">Passée le {{ $order->created_at->format('d M Y à H:i') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $order->status_badge }}">
                {{ $order->status_label }}
            </span>
            <a href="{{ route('admin.orders.index') }}"
               class="px-4 py-2 bg-gray-100 rounded-lg text-sm hover:bg-gray-200 transition">
                ← Retour
            </a>
        </div>
    </div>

    {{-- CLIENT --}}
    <div class="bg-white shadow rounded-2xl p-6 border">
        <h2 class="text-lg font-semibold mb-4">Client</h2>
        {{-- ✅ Nullsafe — user peut être supprimé --}}
        <p><strong>Nom :</strong> {{ $order->user?->name ?? 'Client supprimé' }}</p>
        <p class="mt-1"><strong>Email :</strong> {{ $order->user?->email ?? '—' }}</p>
    </div>

    {{-- ARTICLES --}}
    <div class="bg-white shadow rounded-2xl p-6 border">
        <h2 class="text-lg font-semibold mb-6">Produits commandés</h2>

        <table class="w-full text-left">
            <thead class="border-b text-sm text-gray-500">
                <tr>
                    <th class="pb-3">Produit</th>
                    <th class="pb-3">Prix unitaire</th>
                    <th class="pb-3">Quantité</th>
                    <th class="pb-3 text-right">Sous-total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr class="border-b last:border-0">
                        <td class="py-4 font-medium">
                            {{ $item->product?->name ?? 'Produit supprimé' }}
                        </td>
                        <td class="py-4 text-sm text-gray-500">
                            {{-- ✅ unit_price snapshot — pas $item->price (ancienne colonne supprimée) --}}
                            @if($item->had_promo)
                                <span class="line-through text-gray-400 mr-1">
                                    {{ number_format($item->unit_price, 0, ',', ' ') }}
                                </span>
                                <span class="text-orange-500 font-medium">
                                    {{ number_format($item->unit_price_promo, 0, ',', ' ') }} FCFA
                                </span>
                            @else
                                {{ number_format($item->unit_price, 0, ',', ' ') }} FCFA
                            @endif
                        </td>
                        <td class="py-4">{{ $item->quantity }}</td>
                        <td class="py-4 font-semibold text-orange-600 text-right">
                            {{-- ✅ subtotal accessor du modèle --}}
                            {{ number_format($item->subtotal, 0, ',', ' ') }} FCFA
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-right mt-6 text-xl font-bold border-t pt-4">
            Total : {{ $order->formatted_total }}
        </div>
    </div>

    {{-- CHANGEMENT STATUT --}}
    @if(!$order->is_closed)
        <div class="bg-white shadow rounded-2xl p-6 border">
            <h2 class="text-lg font-semibold mb-4">Modifier le statut</h2>
            <form method="POST"
                  action="{{ route('admin.orders.updateStatus', $order) }}"
                  class="flex items-center gap-4">
                @csrf
                @method('PUT')

                <select name="status"
                        class="border rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    @foreach(App\Models\Order::STATUSES as $s)
                        <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>
                            {{ match($s) {
                                'en_attente'     => 'En attente',
                                'en_preparation' => 'En préparation',
                                'prete'          => 'Prête',
                                'annulee'        => 'Annulée',
                            } }}
                        </option>
                    @endforeach
                </select>

                <button type="submit"
                        class="px-5 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition font-medium">
                    Mettre à jour
                </button>
            </form>
        </div>
    @else
        <div class="bg-gray-50 border rounded-2xl p-5 text-gray-500 text-sm text-center">
            Cette commande est clôturée ({{ $order->status_label }}) — statut non modifiable.
        </div>
    @endif

</div>
@endsection
