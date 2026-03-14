<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Vendeur') — O\'G Délice</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-100 h-screen flex flex-col overflow-hidden">

{{-- NAVBAR VENDEUR --}}
<nav class="bg-white border-b shadow-sm h-16 flex items-center px-6 gap-6 shrink-0 z-40">

    {{-- Logo --}}
    <a href="{{ route('vendeur.pos') }}" class="flex items-center gap-2 shrink-0">
        <div class="w-9 h-9 bg-orange-500 rounded-lg flex items-center justify-center shadow">
            <span class="text-white font-bold text-xs">O'G</span>
        </div>
        <div class="leading-tight hidden sm:block">
            <span class="font-bold text-gray-800 text-sm block">O'G Délice</span>
            <span class="text-xs text-orange-500 font-medium">Mode Vendeur</span>
        </div>
    </a>

    {{-- Nav links --}}
    <div class="flex items-center gap-1 flex-1">
        <a href="{{ route('vendeur.pos') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition
                  {{ request()->routeIs('vendeur.pos') ? 'bg-orange-500 text-white shadow' : 'text-gray-600 hover:bg-orange-50 hover:text-orange-600' }}">
            <span>🖥️</span> Caisse
        </a>
        <a href="{{ route('vendeur.orders.index') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition
                  {{ request()->routeIs('vendeur.orders*') ? 'bg-orange-500 text-white shadow' : 'text-gray-600 hover:bg-orange-50 hover:text-orange-600' }}">
            <span>📋</span> Mes ventes
        </a>
    </div>

    {{-- User --}}
    <div class="flex items-center gap-3 shrink-0" x-data="{ open: false }">
        <div class="text-right hidden sm:block">
            <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
            <p class="text-xs text-orange-500 font-medium">Vendeur</p>
        </div>
        <div class="relative">
            <button @click="open = !open"
                    class="w-9 h-9 bg-orange-100 rounded-full flex items-center justify-center text-orange-600 font-bold text-sm hover:bg-orange-200 transition">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </button>
            <div x-show="open" @click.away="open = false" x-transition
                 class="absolute right-0 mt-2 w-48 bg-white border rounded-xl shadow-xl overflow-hidden z-50">
                <div class="px-4 py-3 bg-gray-50 border-b">
                    <p class="text-xs text-gray-500">Connecté en tant que</p>
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition flex items-center gap-2">
                        <span>🚪</span> Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

{{-- Flash messages --}}
@if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
         class="fixed top-20 right-4 bg-green-500 text-white px-5 py-3 rounded-xl shadow-xl text-sm z-50 flex items-center gap-2">
        ✅ {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="fixed top-20 right-4 bg-red-500 text-white px-5 py-3 rounded-xl shadow-xl text-sm z-50 flex items-center gap-2">
        ❌ {{ session('error') }}
    </div>
@endif

{{-- CONTENU --}}
<main class="flex-1 overflow-hidden relative">
    @yield('content')
</main>

@stack('scripts')
</body>
</html>
