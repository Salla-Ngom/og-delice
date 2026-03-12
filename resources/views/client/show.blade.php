@extends('layouts.client')

@section('title', 'Commande #' . $order->id)

@section('content')
<div class="max-w-4xl mx-auto py-12 px-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-start mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Commande #{{ $order->id }}</h1>
            <p class="text-gray-500 mt-2">Passée le {{ $order->created_at?->format('d M Y à H:i') }}</p>
        </div>
        <span id="status-badge"
              class="px-4 py-2 rounded-full text-sm font-semibold {{ $order->status_badge }}
                     {{ $order->status === 'en_preparation' ? 'animate-pulse' : '' }}">
            {{ $order->status_label }}
        </span>
    </div>

    {{-- PROGRESSION --}}
    @if($order->status !== 'annulee')
        <div class="bg-white shadow-lg rounded-2xl p-8 mb-8 border">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-semibold">Suivi de la commande</h2>
                {{-- Indicateur de mise à jour auto --}}
                <div class="flex items-center gap-2 text-xs text-gray-400">
                    <span id="track-dot" class="w-2 h-2 rounded-full bg-green-400 animate-pulse inline-block"></span>
                    <span id="track-label">Mise à jour automatique</span>
                </div>
            </div>

            @php
                $steps      = [
                    ['label' => 'En attente',    'key' => 'en_attente'],
                    ['label' => 'En préparation', 'key' => 'en_preparation'],
                    ['label' => 'Prête',          'key' => 'prete'],
                ];
                $statusOrder  = ['en_attente' => 1, 'en_preparation' => 2, 'prete' => 3];
                $currentStep  = $statusOrder[$order->status] ?? 1;
            @endphp

            {{-- ✅ id="progress-bar" + data-step — mis à jour par le polling JS --}}
            <div id="order-steps" class="flex items-start justify-between relative"
                 data-order-id="{{ $order->id }}"
                 data-current-step="{{ $currentStep }}">

                <div class="absolute top-5 left-0 right-0 h-1 bg-gray-200 z-0">
                    <div id="progress-bar" class="h-full bg-orange-500 transition-all duration-700"
                         style="width: {{ ($currentStep - 1) * 50 }}%"></div>
                </div>

                @foreach($steps as $index => $step)
                    @php $stepNumber = $index + 1; $reached = $currentStep >= $stepNumber; @endphp
                    <div class="flex flex-col items-center z-10 flex-1 step-item"
                         data-step="{{ $stepNumber }}">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full border-2 transition step-circle
                            {{ $reached ? 'bg-orange-500 border-orange-500 text-white' : 'bg-white border-gray-300 text-gray-400' }}">
                            @if($reached)
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            @else
                                {{ $stepNumber }}
                            @endif
                        </div>
                        <span class="text-xs mt-2 text-center font-medium step-label
                            {{ $reached ? 'text-orange-600' : 'text-gray-400' }}">
                            {{ $step['label'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="bg-red-50 border border-red-200 rounded-2xl p-6 mb-8 text-red-700 font-medium text-center">
            Cette commande a été annulée.
        </div>
    @endif

    {{-- ADRESSE DE LIVRAISON --}}
    @if(auth()->user()->has_delivery_address)
        <div class="bg-orange-50 border border-orange-200 rounded-2xl p-5 mb-8 flex items-start gap-3">
            <span class="text-xl mt-0.5">📍</span>
            <div>
                <p class="text-sm font-semibold text-gray-700">Adresse de livraison</p>
                <p class="text-gray-600 text-sm mt-0.5">{{ auth()->user()->delivery_address }}</p>
            </div>
        </div>
    @endif

    {{-- ARTICLES --}}
    <div class="bg-white shadow-lg rounded-2xl p-8 border">
        <h2 class="text-lg font-semibold mb-6">Détails des articles</h2>

        <div class="space-y-4">
            @foreach($order->items as $item)
                <div class="flex items-center justify-between border-b pb-4 last:border-0 last:pb-0">
                    <div class="flex items-center gap-4">
                        <img src="{{ $item->product?->image_url ?? asset('images/default-product.png') }}"
                             alt="{{ $item->product?->name ?? 'Produit' }}"
                             class="w-16 h-16 object-cover rounded-xl shadow">
                        <div>
                            <p class="font-semibold text-gray-800">
                                {{ $item->product?->name ?? 'Produit supprimé' }}
                            </p>
                            <p class="text-sm text-gray-500">Quantité : {{ $item->quantity }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                @if($item->had_promo)
                                    <span class="line-through mr-1">{{ number_format($item->unit_price, 0, ',', ' ') }}</span>
                                    <span class="text-orange-500">{{ number_format($item->unit_price_promo, 0, ',', ' ') }} FCFA/u</span>
                                @else
                                    {{ number_format($item->unit_price, 0, ',', ' ') }} FCFA/u
                                @endif
                            </p>
                        </div>
                    </div>
                    <p class="font-semibold text-orange-600 whitespace-nowrap">
                        {{ number_format($item->subtotal, 0, ',', ' ') }} FCFA
                    </p>
                </div>
            @endforeach
        </div>

        <div class="mt-8 border-t pt-6 flex justify-between items-center">
            <span class="text-lg font-semibold text-gray-700">Total payé</span>
            <span class="text-2xl font-bold text-orange-600">{{ $order->formatted_total }}</span>
        </div>
    </div>

    <div class="mt-10">
        <a href="{{ route('client.orders') }}"
           class="inline-block px-6 py-3 bg-gray-900 text-white rounded-xl shadow hover:bg-gray-800 transition font-medium">
            ← Retour à mes commandes
        </a>
    </div>

</div>

{{-- ✅ Polling statut — la page se met à jour sans rechargement --}}
@push('scripts')
<script>
(function () {
    const container   = document.getElementById('order-steps');
    if (!container) return; // commande annulée — pas de suivi

    const orderId     = container.dataset.orderId;
    const progressBar = document.getElementById('progress-bar');
    const statusBadge = document.getElementById('status-badge');
    const trackDot    = document.getElementById('track-dot');
    const trackLabel  = document.getElementById('track-label');

    const STATUS_ORDER = { en_attente: 1, en_preparation: 2, prete: 3 };
    const STATUS_LABELS = {
        en_attente:     'En attente',
        en_preparation: 'En préparation',
        prete:          'Prête',
        annulee:        'Annulée',
    };
    const STATUS_BADGES = {
        en_attente:     'bg-yellow-100 text-yellow-700',
        en_preparation: 'bg-blue-100 text-blue-700',
        prete:          'bg-green-100 text-green-700',
        annulee:        'bg-red-100 text-red-700',
    };

    let currentStep = parseInt(container.dataset.currentStep);

    async function pollStatus() {
        try {
            const res  = await fetch(`/client/orders/${orderId}/status`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) throw new Error('HTTP ' + res.status);

            const data = await res.json(); // { status, status_label, status_badge }
            const newStep = STATUS_ORDER[data.status] ?? 1;

            if (newStep !== currentStep) {
                currentStep = newStep;
                updateUI(data.status);

                // Toast discret
                showToast(`Votre commande est maintenant : ${STATUS_LABELS[data.status] ?? data.status}`);

                // Arrêter le polling si commande prête ou annulée
                if (data.status === 'prete' || data.status === 'annulee') {
                    clearInterval(pollInterval);
                    trackDot.className  = 'w-2 h-2 rounded-full bg-gray-400 inline-block';
                    trackLabel.textContent = 'Suivi terminé';
                }
            }
        } catch (e) {
            trackDot.className  = 'w-2 h-2 rounded-full bg-red-400 inline-block';
            trackLabel.textContent = 'Hors ligne';
        }
    }

    function updateUI(status) {
        const newStep = STATUS_ORDER[status] ?? 1;

        // Barre de progression
        progressBar.style.width = ((newStep - 1) * 50) + '%';

        // Cercles des étapes
        container.querySelectorAll('.step-item').forEach(el => {
            const step   = parseInt(el.dataset.step);
            const circle = el.querySelector('.step-circle');
            const label  = el.querySelector('.step-label');
            const reached = newStep >= step;

            circle.className = 'w-10 h-10 flex items-center justify-center rounded-full border-2 transition step-circle '
                + (reached ? 'bg-orange-500 border-orange-500 text-white' : 'bg-white border-gray-300 text-gray-400');

            circle.innerHTML = reached
                ? `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`
                : step;

            label.className = 'text-xs mt-2 text-center font-medium step-label '
                + (reached ? 'text-orange-600' : 'text-gray-400');
        });

        // Badge statut en haut
        if (statusBadge) {
            statusBadge.className = `px-4 py-2 rounded-full text-sm font-semibold ${STATUS_BADGES[status] ?? ''}`;
            statusBadge.textContent = STATUS_LABELS[status] ?? status;
        }
    }

    function showToast(message) {
        const t = document.createElement('div');
        t.className = 'fixed bottom-6 right-6 z-50 bg-gray-900 text-white px-5 py-3 rounded-2xl shadow-2xl text-sm font-medium flex items-center gap-2 transition-all duration-500 opacity-0 translate-y-4';
        t.innerHTML = `<span class="text-orange-400 text-lg">🍽️</span> ${message}`;
        document.body.appendChild(t);
        requestAnimationFrame(() => { t.classList.remove('opacity-0', 'translate-y-4'); t.classList.add('opacity-100', 'translate-y-0'); });
        setTimeout(() => { t.classList.add('opacity-0'); setTimeout(() => t.remove(), 500); }, 5000);
    }

    // Poll toutes les 20s — plus fréquent que le dashboard admin car le client attend activement
    const pollInterval = setInterval(pollStatus, 20000);

    // Poll immédiat si l'onglet redevient actif
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') pollStatus();
    });
})();
</script>
@endpush

@endsection
