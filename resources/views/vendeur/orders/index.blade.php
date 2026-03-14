@extends('layouts.vendeur')

@section('title', 'Mes ventes')

@section('content')
<div class="max-w-5xl mx-auto py-8 px-6 overflow-y-auto h-full">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Mes ventes</h1>
        <a href="{{ route('vendeur.pos') }}"
           class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition shadow">
            🖥️ Ouvrir la caisse
        </a>
    </div>

    {{-- Stats du jour --}}
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-2xl border shadow-sm p-5">
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-1">Ventes aujourd'hui</p>
            <p class="text-3xl font-bold text-gray-800">{{ $todayCount }}</p>
        </div>
        <div class="bg-white rounded-2xl border shadow-sm p-5">
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-1">Chiffre du jour</p>
            <p class="text-3xl font-bold text-orange-600">{{ number_format($todayTotal, 0, ',', ' ') }} <span class="text-base font-normal">FCFA</span></p>
        </div>
    </div>

    {{-- Liste ventes --}}
    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr class="text-gray-500 text-xs uppercase tracking-wide">
                    <th class="px-5 py-3 text-left">Référence</th>
                    <th class="px-5 py-3 text-left">Client</th>
                    <th class="px-5 py-3 text-left">Articles</th>
                    <th class="px-5 py-3 text-right">Total</th>
                    <th class="px-5 py-3 text-left">Date</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 font-mono font-bold text-orange-600">
                            {{ $order->reference }}
                        </td>
                        <td class="px-5 py-3 text-gray-600">
                            {{ $order->customer_name ?: '—' }}
                        </td>
                        <td class="px-5 py-3 text-gray-500">
                            {{ $order->items->sum('quantity') }} article(s)
                        </td>
                        <td class="px-5 py-3 text-right font-semibold text-gray-800">
                            {{ number_format($order->total_price, 0, ',', ' ') }} FCFA
                        </td>
                        <td class="px-5 py-3 text-gray-400 text-xs">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-5 py-3">
                            <a href="{{ route('vendeur.orders.receipt', $order) }}" target="_blank"
                               class="text-orange-500 hover:text-orange-700 font-medium text-xs flex items-center gap-1 whitespace-nowrap">
                                🖨️ Reçu
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-16 text-center text-gray-400">
                            Aucune vente enregistrée.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($orders->hasPages())
            <div class="px-5 py-4 border-t">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
