@extends('layouts.client')

@section('title', 'Demande envoyée')

@section('content')
<div class="max-w-lg mx-auto py-24 px-6 text-center">

    <div class="text-7xl mb-6 animate-bounce">✅</div>

    <h1 class="text-2xl font-bold text-gray-800 mb-3">Demande envoyée !</h1>

    <p class="text-gray-500 leading-relaxed mb-8">
        Merci pour votre confiance. Notre équipe a bien reçu votre demande
        et vous contactera dans les <strong>24 heures</strong> à l'adresse
        email que vous avez indiquée.
    </p>

    @if(session('catering_ref'))
        <div class="bg-orange-50 border border-orange-200 rounded-2xl px-6 py-4 mb-8 inline-block">
            <p class="text-xs text-gray-500 mb-1">Référence de votre demande</p>
            <p class="font-mono font-bold text-orange-600 text-lg">
                TRT-{{ str_pad(session('catering_ref'), 5, '0', STR_PAD_LEFT) }}
            </p>
        </div>
    @endif

    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <a href="{{ route('home') }}"
           class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-8 py-3 rounded-xl transition shadow">
            Retour à l'accueil
        </a>
        <a href="{{ route('menu') }}"
           class="border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold px-8 py-3 rounded-xl transition">
            Voir le menu
        </a>
    </div>

</div>
@endsection
