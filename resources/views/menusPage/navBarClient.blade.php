@php
    $cart      = session('cart', []);
    $cartCount = array_sum(array_column($cart, 'quantity'));
@endphp

{{-- ✅ x-data sur le fragment racine, PAS sur <nav>
     x-teleport partage le scope Alpine si le <div> téléporté est FRÈRE dans le même x-data --}}
<div x-data="{ open: false, mobile: false }">

<nav class="fixed top-0 left-0 w-full bg-white/90 backdrop-blur-md shadow-sm border-b z-50">

    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

        {{-- LOGO --}}
        <a href="{{ route('client.dashboard') }}" class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center shadow">
                <span class="text-white font-bold text-sm">O'G</span>
            </div>
            <div class="leading-tight">
                <span class="text-xl font-bold text-gray-800 block">O'G Délice</span>
                <span class="text-xs text-gray-500 block">Restaurant • Fast-Food • Traiteur</span>
            </div>
        </a>

        {{-- MENU DESKTOP --}}
        <div class="hidden md:flex items-center gap-8 font-medium text-gray-700 text-sm">

            <a href="{{ route('client.dashboard') }}"
               class="relative hover:text-orange-500 transition
                      {{ request()->routeIs('client.dashboard') ? 'text-orange-500 after:w-full' : 'after:w-0 hover:after:w-full' }}
                      after:absolute after:left-0 after:-bottom-1 after:h-[2px] after:bg-orange-500 after:transition-all">
                Tableau de bord
            </a>

            <a href="{{ route('menu') }}"
               class="relative hover:text-orange-500 transition
                      {{ request()->routeIs('menu') ? 'text-orange-500 after:w-full' : 'after:w-0 hover:after:w-full' }}
                      after:absolute after:left-0 after:-bottom-1 after:h-[2px] after:bg-orange-500 after:transition-all">
                Menu
            </a>

            <a href="{{ route('client.orders') }}"
               class="relative hover:text-orange-500 transition
                      {{ request()->routeIs('client.orders*') ? 'text-orange-500 after:w-full' : 'after:w-0 hover:after:w-full' }}
                      after:absolute after:left-0 after:-bottom-1 after:h-[2px] after:bg-orange-500 after:transition-all">
                Mes commandes
            </a>

            {{-- PANIER --}}
            <a href="{{ route('cart.index') }}" class="relative hover:text-orange-500 transition text-xl" title="Mon panier">
                🛒
                @if($cartCount > 0)
                    <span class="absolute -top-2 -right-3 bg-red-500 text-white text-xs min-w-[20px] h-5 flex items-center justify-center rounded-full px-1 font-bold">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>

            {{-- DROPDOWN UTILISATEUR --}}
            @auth
                <div class="relative">
                    <button @click="open = !open"
                            class="bg-orange-500 text-white px-5 py-2 rounded-lg shadow hover:bg-orange-600 transition flex items-center gap-2">
                        <span class="max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                        <svg class="w-4 h-4 shrink-0 transition" :class="open ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition
                         class="absolute right-0 mt-3 w-56 bg-white shadow-xl rounded-xl border overflow-hidden">
                        <div class="px-4 py-3 bg-gray-50 border-b">
                            <p class="font-semibold text-gray-800 truncate text-sm">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-3 hover:bg-orange-50 hover:text-orange-600 transition text-sm">
                            <span>✏️</span> Mon profil
                        </a>
                        <div class="border-t"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="w-full text-left flex items-center gap-2 px-4 py-3 hover:bg-red-50 transition text-red-600 text-sm">
                                <span>🚪</span> Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            @endauth

        </div>

        {{-- MOBILE : panier + burger --}}
        <div class="md:hidden flex items-center gap-4">
            <a href="{{ route('cart.index') }}" class="relative text-xl">
                🛒
                @if($cartCount > 0)
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full font-bold">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>
            {{-- ✅ @click="mobile = !mobile" dans le même x-data que le menu --}}
            <button @click="mobile = !mobile" class="text-gray-700 text-2xl p-1" aria-label="Menu">☰</button>
        </div>

    </div>

</nav>

{{-- ✅ MOBILE MENU — frère de <nav> dans le même x-data
     x-teleport="body" place le rendu directement dans <body>
     mais le scope Alpine est partagé car c'est dans le même <div x-data> --}}
<template x-teleport="body">
    <div x-show="mobile"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="fixed inset-0 bg-white z-[9999] p-8 overflow-y-auto md:hidden">

        <div class="flex justify-between items-center mb-8">
            <span class="text-xl font-bold text-orange-600">O'G Délice</span>
            <button @click="mobile = false" class="text-2xl text-gray-700 p-1" aria-label="Fermer">✕</button>
        </div>

        <div class="space-y-1 text-gray-700">

            <a href="{{ route('client.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-orange-50 hover:text-orange-600 transition {{ request()->routeIs('client.dashboard') ? 'bg-orange-50 text-orange-600 font-semibold' : '' }}">
                <span>📊</span> Tableau de bord
            </a>

            <a href="{{ route('menu') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-orange-50 hover:text-orange-600 transition {{ request()->routeIs('menu') ? 'bg-orange-50 text-orange-600 font-semibold' : '' }}">
                <span>🍽️</span> Menu
            </a>

            <a href="{{ route('client.orders') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-orange-50 hover:text-orange-600 transition {{ request()->routeIs('client.orders*') ? 'bg-orange-50 text-orange-600 font-semibold' : '' }}">
                <span>📦</span> Mes commandes
            </a>

            <a href="{{ route('cart.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-orange-50 hover:text-orange-600 transition">
                <span>🛒</span> Panier
                @if($cartCount > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full font-bold">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>

            @auth
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-orange-50 hover:text-orange-600 transition">
                    <span>✏️</span> Mon profil
                </a>

                <div class="pt-4 border-t mt-4">
                    <div class="px-4 py-3 bg-gray-50 rounded-xl mb-3">
                        <p class="text-xs text-gray-500">Connecté en tant que</p>
                        <p class="font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                        @if(auth()->user()->phone)
                            <p class="text-xs text-gray-500 mt-0.5">
                                🇸🇳 +221 {{ auth()->user()->formatted_phone }}
                            </p>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="flex items-center gap-3 px-4 py-3 w-full text-red-600 hover:bg-red-50 rounded-xl transition">
                            <span>🚪</span> Déconnexion
                        </button>
                    </form>
                </div>
            @endauth

        </div>
    </div>
</template>

</div>{{-- fin x-data --}}