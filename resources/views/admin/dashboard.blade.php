@extends('layouts.admin')

@section('content')

<div class="space-y-10">

    {{-- HEADER PREMIUM --}}
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-4xl font-bold text-gray-800 tracking-tight">
                Dashboard
            </h1>
            <p class="text-gray-500 mt-2">
                Vue globale des performances de O'G Délice
            </p>
        </div>

        <div class="bg-white shadow-md rounded-2xl px-6 py-3 border text-sm text-gray-600">
            📅 {{ now()->format('d M Y') }}
        </div>
    </div>


    {{-- STAT CARDS PREMIUM --}}
    <div class="grid md:grid-cols-4 gap-8">

        {{-- USERS --}}
        <div class="relative bg-white p-6 rounded-3xl shadow-md hover:shadow-2xl transition duration-300 border overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-100 rounded-full blur-2xl opacity-30"></div>

            <p class="text-sm text-gray-500">Utilisateurs</p>
            <h2 class="text-4xl font-bold text-gray-800 mt-2">
                {{ $totalUsers }}
            </h2>
            <p class="text-xs text-gray-400 mt-1">Comptes enregistrés</p>
        </div>

        {{-- PRODUITS --}}
        <div class="relative bg-white p-6 rounded-3xl shadow-md hover:shadow-2xl transition duration-300 border overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-green-100 rounded-full blur-2xl opacity-30"></div>

            <p class="text-sm text-gray-500">Produits</p>
            <h2 class="text-4xl font-bold text-gray-800 mt-2">
                {{ $totalProducts }}
            </h2>
            <p class="text-xs text-gray-400 mt-1">Articles disponibles</p>
        </div>

        {{-- COMMANDES --}}
        <div class="relative bg-white p-6 rounded-3xl shadow-md hover:shadow-2xl transition duration-300 border overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-purple-100 rounded-full blur-2xl opacity-30"></div>

            <p class="text-sm text-gray-500">Commandes</p>
            <h2 class="text-4xl font-bold text-gray-800 mt-2">
                {{ $totalOrders }}
            </h2>
            <p class="text-xs text-gray-400 mt-1">Commandes totales</p>
        </div>

        {{-- REVENUE --}}
        <div class="relative bg-gradient-to-r from-orange-500 to-red-500 p-6 rounded-3xl shadow-xl text-white overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl"></div>

            <p class="text-sm opacity-80">Chiffre d'affaires</p>
            <h2 class="text-4xl font-bold mt-2">
                {{ number_format($totalRevenue,0,',',' ') }} FCFA
            </h2>
            <p class="text-xs opacity-70 mt-1">Revenus validés</p>
        </div>

    </div>


    {{-- GRAPH + RECENT ORDERS --}}
    <div class="grid md:grid-cols-3 gap-10">

        {{-- GRAPH PREMIUM --}}
        <div class="md:col-span-2 bg-white p-8 rounded-3xl shadow-xl border">
            <h2 class="text-xl font-semibold text-gray-700 mb-6">
                📊 Ventes sur 7 jours
            </h2>

            <canvas id="salesChart" height="120"></canvas>
        </div>


        {{-- RECENT ORDERS PREMIUM --}}
        <div class="bg-white p-8 rounded-3xl shadow-xl border">
            <h2 class="text-xl font-semibold text-gray-700 mb-6">
                🕒 Commandes récentes
            </h2>

            @forelse($recentOrders as $order)
                <div class="flex justify-between items-center py-4 border-b last:border-none hover:bg-gray-50 rounded-xl px-3 transition">
                    <div>
                        <p class="font-semibold text-gray-800">
                            {{ $order->user->name }}
                        </p>
                        <p class="text-xs text-gray-400">
                            {{ optional($order->created_at)->diffForHumans() }}
                        </p>
                    </div>

                    <div class="text-right">
                        <p class="font-semibold text-orange-600">
                            {{ number_format($order->total_price,0,',',' ') }} FCFA
                        </p>
                        <span class="text-xs px-3 py-1 rounded-full
                            {{ $order->status == 'en_attente' ? 'bg-yellow-100 text-yellow-600' : '' }}
                            {{ $order->status == 'en_preparation' ? 'bg-blue-100 text-blue-600' : '' }}
                            {{ $order->status == 'prete' ? 'bg-green-100 text-green-600' : '' }}">
                            {{ $order->status }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-gray-400 text-sm">
                    Aucune commande récente
                </p>
            @endforelse

        </div>

    </div>

</div>


{{-- ChartJS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Ventes',
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
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f3f4f6' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
</script>

@endsection
