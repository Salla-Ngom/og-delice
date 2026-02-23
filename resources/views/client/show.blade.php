@extends('layouts.client')

@section('content')

<div class="max-w-6xl mx-auto py-12 px-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-10">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Commande #{{ $order->id }}
            </h1>
            <p class="text-gray-500 mt-2">
                Passée le {{ $order->created_at?->format('d M Y à H:i') }}
            </p>
        </div>

        <span class="px-4 py-2 rounded-full text-sm font-semibold
            {{ $order->status_badge }}
            {{ $order->status == 'en_preparation' ? 'animate-pulse' : '' }}">
            {{ $order->status_label }}
        </span>
    </div>


    {{-- PROGRESSION --}}
    <div class="bg-white shadow-lg rounded-2xl p-8 mb-10 border">

        <h2 class="text-lg font-semibold mb-6">
            Suivi de la commande
        </h2>

        <div class="flex items-center justify-between">

            @php
                $steps = [
                    'en_attente' => 1,
                    'en_preparation' => 2,
                    'prete' => 3,
                ];

                $currentStep = $steps[$order->status] ?? 1;
            @endphp

            @foreach(['En attente','En préparation','Prête'] as $index => $label)

                @php $stepNumber = $index + 1; @endphp

                <div class="flex flex-col items-center flex-1 relative">

                    <div class="w-10 h-10 flex items-center justify-center rounded-full
                        {{ $currentStep >= $stepNumber
                            ? 'bg-orange-500 text-white'
                            : 'bg-gray-200 text-gray-500' }}">
                        {{ $stepNumber }}
                    </div>

                    <span class="text-sm mt-2
                        {{ $currentStep >= $stepNumber
                            ? 'text-orange-600 font-semibold'
                            : 'text-gray-400' }}">
                        {{ $label }}
                    </span>

                    @if($stepNumber < 3)
                        <div class="absolute top-5 right-0 w-full h-1
                            {{ $currentStep > $stepNumber
                                ? 'bg-orange-500'
                                : 'bg-gray-200' }}"
                            style="left:50%; width:100%; z-index:-1;">
                        </div>
                    @endif

                </div>

            @endforeach

        </div>

    </div>


    {{-- PRODUITS --}}
    <div class="bg-white shadow-lg rounded-2xl p-8 border">

        <h2 class="text-lg font-semibold mb-6">
            Détails des articles
        </h2>

        <div class="space-y-6">

            @foreach($order->items as $item)
                <div class="flex items-center justify-between border-b pb-4">

                    <div class="flex items-center gap-4">

                        @if($item->product->image)
                            <img src="{{ asset('storage/'.$item->product->image) }}"
                                 class="w-16 h-16 object-cover rounded-lg shadow">
                        @endif

                        <div>
                            <p class="font-semibold text-gray-800">
                                {{ $item->product->name }}
                            </p>
                            <p class="text-sm text-gray-500">
                                Quantité : {{ $item->quantity }}
                            </p>
                        </div>

                    </div>

                    <p class="font-semibold text-orange-600">
                        {{ number_format($item->price * $item->quantity,0,',',' ') }} FCFA
                    </p>

                </div>
            @endforeach

        </div>


        {{-- TOTAL --}}
        <div class="mt-8 border-t pt-6 flex justify-between items-center">
            <span class="text-lg font-semibold text-gray-700">
                Total payé
            </span>
            <span class="text-2xl font-bold text-orange-600">
                {{ number_format($order->total_price,0,',',' ') }} FCFA
            </span>
        </div>

    </div>


    {{-- BOUTON RETOUR --}}
    <div class="mt-10">
        <a href="{{ route('client.orders') }}"
           class="inline-block px-6 py-3 bg-gray-900 text-white rounded-xl shadow hover:bg-gray-800 transition">
            ← Retour à mes commandes
        </a>
    </div>

</div>

@endsection