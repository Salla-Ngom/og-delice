@extends('layouts.client')

@section('content')

<div class="max-w-6xl mx-auto py-10 px-6">

    <h1 class="text-2xl font-bold mb-8">Mes Commandes</h1>

    @forelse($orders as $order)
        <div class="bg-white shadow rounded-xl p-6 mb-6">
            <div class="flex justify-between mb-4">
                <div>
                    <p class="font-semibold">Commande #{{ $order->id }}</p>
                    <p class="text-sm text-gray-500">
                       {{ $order->created_at?->format('d M Y à H:i') }}
                    </p>
                </div>
                <span class="px-3 py-1 rounded-full text-sm
                    @if($order->status == 'en_attente') bg-yellow-100 text-yellow-700
                    @elseif($order->status == 'en_preparation') bg-blue-100 text-blue-700
                    @elseif($order->status == 'prete') bg-green-100 text-green-700
                    @else bg-red-100 text-red-700
                    @endif
                ">
                    {{ $order->status }}
                </span>
            </div>

            <p class="font-bold text-orange-600">
                Total : {{ number_format($order->total_price,0,',',' ') }} FCFA
            </p>
        </div>
    @empty
        <p>Aucune commande trouvée.</p>
    @endforelse

</div>

@endsection
