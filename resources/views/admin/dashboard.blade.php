@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-10">

    {{-- HEADER --}}
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-4xl font-bold text-gray-800 tracking-tight">Dashboard</h1>
            <p class="text-gray-500 mt-2">Vue globale des performances de O'G Délice</p>
        </div>
        <div class="bg-white shadow-md rounded-2xl px-6 py-3 border text-sm text-gray-600">
            📅 {{ now()->translatedFormat('d M Y') }}
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">

        <div class="relative bg-white p-6 rounded-3xl shadow-md hover:shadow-xl transition border overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-100 rounded-full blur-2xl opacity-30"></div>
            <p class="text-sm text-gray-500">Utilisateurs</p>
            <h2 class="text-4xl font-bold text-gray-800 mt-2">{{ $totalUsers }}</h2>
            <p class="text-xs text-gray-400 mt-1">Comptes enregistrés</p>
        </div>

        <div class="relative bg-white p-6 rounded-3xl shadow-md hover:shadow-xl transition border overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-green-100 rounded-full blur-2xl opacity-30"></div>
            <p class="text-sm text-gray-500">Produits</p>
            <h2 class="text-4xl font-bold text-gray-800 mt-2">{{ $totalProducts }}</h2>
            <p class="text-xs text-gray-400 mt-1">Articles disponibles</p>
        </div>

        {{-- ✅ Ajout pendingOrders et lowStock depuis le contrôleur --}}
        <div class="relative bg-white p-6 rounded-3xl shadow-md hover:shadow-xl transition border overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-yellow-100 rounded-full blur-2xl opacity-30"></div>
            <p class="text-sm text-gray-500">En attente</p>
            <h2 class="text-4xl font-bold text-gray-800 mt-2">{{ $pendingOrders }}</h2>
            <p class="text-xs text-gray-400 mt-1">Commandes à traiter</p>
        </div>

        <div class="relative bg-gradient-to-r from-orange-500 to-red-500 p-6 rounded-3xl shadow-xl text-white overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl"></div>
            <p class="text-sm opacity-80">Chiffre d'affaires</p>
            <h2 class="text-4xl font-bold mt-2">
                {{ number_format($totalRevenue, 0, ',', ' ') }} FCFA
            </h2>
            <p class="text-xs opacity-70 mt-1">Commandes validées uniquement</p>
        </div>

    </div>

    {{-- Alerte stock faible --}}
    @if($lowStock > 0)
        <div class="bg-amber-50 border border-amber-200 text-amber-800 px-5 py-4 rounded-xl flex items-center gap-3">
            <span class="text-xl">⚠️</span>
            <span>
                <strong>{{ $lowStock }} produit(s)</strong> ont un stock inférieur à 5 unités.
                <a href="{{ route('admin.products.index') }}" class="underline ml-1 font-medium">Voir les produits →</a>
            </span>
        </div>
    @endif

    {{-- GRAPHIQUE + COMMANDES RÉCENTES --}}
    <div class="grid md:grid-cols-3 gap-8">

        {{-- GRAPHIQUE --}}
        <div class="md:col-span-2 bg-white p-8 rounded-3xl shadow-xl border">
            <h2 class="text-xl font-semibold text-gray-700 mb-6">📊 Ventes sur 7 jours</h2>
            <canvas id="salesChart" height="120"></canvas>
        </div>

        {{-- COMMANDES RÉCENTES --}}
        <div class="bg-white p-8 rounded-3xl shadow-xl border">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-700">🕒 Récentes</h2>
                <a href="{{ route('admin.orders.index') }}"
                   class="text-orange-500 text-sm hover:underline font-medium">Toutes →</a>
            </div>

            @forelse($recentOrders as $order)
                <a href="{{ route('admin.orders.show', $order) }}"
                   class="flex justify-between items-center py-3 border-b last:border-0 hover:bg-gray-50 rounded-xl px-3 transition block">
                    <div>
                        {{-- ✅ Nullsafe operator — user peut être supprimé --}}
                        <p class="font-semibold text-gray-800 text-sm">{{ $order->user?->name ?? 'Client supprimé' }}</p>
                        <p class="text-xs text-gray-400">{{ $order->created_at?->diffForHumans() }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-orange-600 text-sm">
                            {{ $order->formatted_total }}
                        </p>
                        {{-- ✅ Utilise l'accessor status_label et status_badge du modèle
                             ❌ SUPPRIMÉ : ternaires empilés {{ $order->status == ... ? ... : '' }} --}}
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $order->status_badge }}">
                            {{ $order->status_label }}
                        </span>
                    </div>
                </a>
            @empty
                <p class="text-gray-400 text-sm text-center py-6">Aucune commande récente</p>
            @endforelse
        </div>

    </div>

</div>

{{-- ✅ Chart.js via @push — chargé dans le bon ordre après le canvas --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('salesChart'), {
    type: 'line',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'Ventes (FCFA)',
            data: @json($chartValues),
            borderColor: '#f97316',
            backgroundColor: 'rgba(249,115,22,0.08)',
            fill: true,
            borderWidth: 3,
            tension: 0.4,
            pointRadius: 4,
            pointBackgroundColor: '#f97316'
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f3f4f6' } },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endpush

@endsection
