@extends('layouts.admin')

@section('title', 'Demande TRT-' . str_pad($catering->id, 5, '0', STR_PAD_LEFT))

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Demande TRT-{{ str_pad($catering->id, 5, '0', STR_PAD_LEFT) }}
            </h1>
            <p class="text-gray-500 mt-1">Reçue le {{ $catering->created_at->format('d M Y à H:i') }}</p>
        </div>
        <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $catering->status_badge }}">
            {{ $catering->status_label }}
        </span>
    </div>

    <div class="grid md:grid-cols-2 gap-6">

        {{-- INFOS DEMANDEUR --}}
        <div class="bg-white rounded-2xl border shadow-sm p-6">
            <h2 class="text-base font-semibold text-gray-700 mb-4">Contact</h2>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Nom</span>
                    <span class="font-medium text-gray-800">{{ $catering->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Email</span>
                    <a href="mailto:{{ $catering->email }}"
                       class="text-orange-600 hover:underline">{{ $catering->email }}</a>
                </div>
                @if($catering->phone)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Téléphone</span>
                        <a href="tel:+221{{ $catering->phone }}"
                           class="text-orange-600 hover:underline">+221 {{ $catering->phone }}</a>
                    </div>
                @endif
                @if($catering->user)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Compte client</span>
                        <span class="text-green-600 text-xs font-medium">✅ Inscrit</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- INFOS ÉVÉNEMENT --}}
        <div class="bg-white rounded-2xl border shadow-sm p-6">
            <h2 class="text-base font-semibold text-gray-700 mb-4">Événement</h2>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Type</span>
                    <span class="font-medium text-gray-800">{{ $catering->event_type_label }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Date</span>
                    <span class="font-medium text-gray-800">{{ $catering->formatted_date }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Personnes</span>
                    <span class="font-medium text-gray-800">{{ number_format($catering->guests, 0, ',', ' ') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Budget</span>
                    <span class="font-medium text-orange-600">{{ $catering->formatted_budget }}</span>
                </div>
            </div>
        </div>

    </div>

    {{-- MESSAGE CLIENT --}}
    @if($catering->message)
        <div class="bg-white rounded-2xl border shadow-sm p-6">
            <h2 class="text-base font-semibold text-gray-700 mb-3">Message du client</h2>
            <p class="text-gray-600 text-sm leading-relaxed whitespace-pre-line">
                {{ $catering->message }}
            </p>
        </div>
    @endif

    {{-- RÉPONSE EXISTANTE --}}
    @if($catering->admin_response)
        <div class="bg-green-50 border border-green-200 rounded-2xl p-6">
            <div class="flex justify-between items-start mb-3">
                <h2 class="text-base font-semibold text-green-800">Réponse envoyée</h2>
                <span class="text-xs text-green-600">
                    {{ $catering->responded_at?->format('d M Y à H:i') }}
                    — {{ $catering->respondedBy?->name ?? 'Admin' }}
                </span>
            </div>
            <p class="text-green-700 text-sm leading-relaxed whitespace-pre-line">
                {{ $catering->admin_response }}
            </p>
        </div>
    @endif

    {{-- FORMULAIRE RÉPONSE + STATUT --}}
    <div class="bg-white rounded-2xl border shadow-sm p-6">
        <h2 class="text-base font-semibold text-gray-700 mb-5">
            {{ $catering->admin_response ? 'Modifier la réponse' : 'Répondre à cette demande' }}
        </h2>

        <form action="{{ route('admin.catering.respond', $catering) }}" method="POST" class="space-y-5">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Statut de la demande
                </label>
                <select name="status"
                        class="w-full border rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none">
                    @foreach(['nouvelle' => 'Nouvelle', 'en_cours' => 'En cours de traitement', 'acceptee' => 'Acceptée ✅', 'refusee' => 'Refusée ❌'] as $val => $label)
                        <option value="{{ $val }}" {{ $catering->status === $val ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Votre message <span class="text-red-500">*</span>
                </label>
                <textarea name="admin_response" rows="5"
                          placeholder="Bonjour M./Mme ..., nous avons bien reçu votre demande..."
                          class="w-full border rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none resize-none
                                 @error('admin_response') border-red-400 @enderror">{{ old('admin_response', $catering->admin_response) }}</textarea>
                @error('admin_response')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit"
                        class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-8 py-3 rounded-xl transition shadow">
                    Enregistrer la réponse
                </button>
                <a href="{{ route('admin.catering.index') }}"
                   class="border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold px-6 py-3 rounded-xl transition">
                    ← Retour
                </a>
            </div>
        </form>
    </div>

    {{-- DANGER ZONE --}}
    <div class="bg-white rounded-2xl border border-red-100 p-5 flex justify-between items-center">
        <div>
            <p class="text-sm font-medium text-gray-700">Supprimer cette demande</p>
            <p class="text-xs text-gray-400 mt-0.5">Action irréversible.</p>
        </div>
        <form action="{{ route('admin.catering.destroy', $catering) }}" method="POST"
              onsubmit="return confirm('Supprimer définitivement cette demande ?')">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="bg-red-500 hover:bg-red-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition">
                Supprimer
            </button>
        </form>
    </div>

</div>
@endsection
