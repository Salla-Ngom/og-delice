@extends('layouts.admin')

@section('title', 'Demandes Traiteur')

@section('content')
<div class="space-y-6">

    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Demandes Traiteur</h1>
            <p class="text-gray-500">Gestion des événements et prestations</p>
        </div>
    </div>

    {{-- STATS STATUTS --}}
    <div class="grid grid-cols-4 gap-4">
        @foreach(['nouvelle' => ['Nouvelles','blue'], 'en_cours' => ['En cours','yellow'], 'acceptee' => ['Acceptées','green'], 'refusee' => ['Refusées','red']] as $st => [$label, $color])
            <a href="{{ route('admin.catering.index', ['status' => $st]) }}"
               class="bg-white rounded-2xl border shadow-sm p-5 hover:border-orange-300 transition
                      {{ $status === $st ? 'border-orange-400 ring-2 ring-orange-200' : '' }}">
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-1">{{ $label }}</p>
                <p class="text-3xl font-bold text-gray-800">{{ $counts[$st] }}</p>
            </a>
        @endforeach
    </div>

    {{-- FILTRES --}}
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.catering.index') }}"
           class="px-4 py-2 rounded-full text-sm font-medium transition
                  {{ !$status ? 'bg-orange-500 text-white' : 'bg-white border text-gray-600 hover:border-orange-400' }}">
            Toutes
        </a>
        @foreach(['nouvelle' => 'Nouvelles', 'en_cours' => 'En cours', 'acceptee' => 'Acceptées', 'refusee' => 'Refusées'] as $val => $label)
            <a href="{{ route('admin.catering.index', ['status' => $val]) }}"
               class="px-4 py-2 rounded-full text-sm font-medium transition
                      {{ $status === $val ? 'bg-orange-500 text-white' : 'bg-white border text-gray-600 hover:border-orange-400' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- TABLEAU --}}
    <div class="bg-white shadow-xl rounded-2xl border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 border-b text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="p-4">Référence</th>
                        <th class="p-4">Demandeur</th>
                        <th class="p-4">Événement</th>
                        <th class="p-4">Date</th>
                        <th class="p-4">Personnes</th>
                        <th class="p-4">Budget</th>
                        <th class="p-4">Statut</th>
                        <th class="p-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $req)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="p-4 font-mono text-orange-600 font-bold">
                                TRT-{{ str_pad($req->id, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="p-4">
                                <p class="font-medium text-gray-800">{{ $req->name }}</p>
                                <p class="text-xs text-gray-400">{{ $req->email }}</p>
                            </td>
                            <td class="p-4">{{ $req->event_type_label }}</td>
                            <td class="p-4 text-gray-600 whitespace-nowrap">
                                {{ $req->event_date?->format('d M Y') }}
                            </td>
                            <td class="p-4 text-gray-600">{{ number_format($req->guests, 0, ',', ' ') }} pers.</td>
                            <td class="p-4 text-gray-600">{{ $req->formatted_budget }}</td>
                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $req->status_badge }}">
                                    {{ $req->status_label }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <a href="{{ route('admin.catering.show', $req) }}"
                                   class="text-orange-600 hover:underline font-medium text-sm">
                                    Voir →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-12 text-center text-gray-400">
                                Aucune demande traiteur.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($requests->hasPages())
            <div class="p-5 border-t">{{ $requests->links() }}</div>
        @endif
    </div>

</div>
@endsection
