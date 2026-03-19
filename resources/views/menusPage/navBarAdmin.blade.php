<aside id="admin-sidebar" class="fixed inset-y-0 left-0 w-64 bg-gray-950 text-gray-300 shadow-2xl flex flex-col">

    {{-- LOGO --}}
    <div class="p-6 border-b border-gray-800">
        <h1 class="text-2xl font-bold text-white tracking-wide">O'G Admin</h1>
        <p class="text-xs text-gray-500 mt-1">Gestion Restaurant</p>
    </div>

    {{-- NAVIGATION --}}
    <nav class="flex-1 p-4 space-y-2 text-sm">

        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition
           {{ request()->routeIs('admin.dashboard') ? 'bg-orange-500 text-white shadow-lg' : 'hover:bg-gray-800' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13h8V3H3v10zM13 21h8v-6h-8v6zM13 3v8h8V3h-8zM3 21h8v-4H3v4z"/>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('admin.products.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition
           {{ request()->routeIs('admin.products.*') ? 'bg-orange-500 text-white shadow-lg' : 'hover:bg-gray-800' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V7a2 2 0 00-2-2h-3l-2-2-2 2H6a2 2 0 00-2 2v6"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 13h16v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6z"/>
            </svg>
            Produits
        </a>

        {{-- COMMANDES + badge dynamique --}}
        <a href="{{ route('admin.orders.index') }}"
           class="flex items-center justify-between px-4 py-3 rounded-xl transition
           {{ request()->routeIs('admin.orders.*') ? 'bg-orange-500 text-white shadow-lg' : 'hover:bg-gray-800' }}">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6h13M9 7h13M5 7h.01M5 11h.01M5 15h.01"/>
                </svg>
                Commandes
            </div>
            {{-- ✅ id="badge-pending" — mis à jour par le polling JS --}}
            <span id="badge-pending"
                  class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full font-bold transition-all duration-500
                         {{ auth()->user()->unreadNotifications->count() === 0 ? 'hidden' : 'animate-pulse' }}">
                {{ auth()->user()->unreadNotifications->count() }}
            </span>
        </a>

        {{-- NOTIFICATIONS + badge dynamique --}}
        <a href="{{ route('admin.notifications') }}"
           class="flex items-center justify-between px-4 py-3 rounded-xl transition
           {{ request()->routeIs('admin.notifications') ? 'bg-orange-500 text-white shadow-lg' : 'hover:bg-gray-800' }}">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                Notifications
            </div>
            {{-- ✅ id="badge-notifs" — mis à jour par le polling JS --}}
            <span id="badge-notifs"
                  class="bg-orange-400 text-white text-xs px-2 py-0.5 rounded-full font-bold transition-all duration-500
                         {{ auth()->user()->unreadNotifications->count() === 0 ? 'hidden' : '' }}">
                {{ auth()->user()->unreadNotifications->count() }}
            </span>
        </a>

        <a href="{{ route('admin.users.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition
           {{ request()->routeIs('admin.users.*') ? 'bg-orange-500 text-white shadow-lg' : 'hover:bg-gray-800' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
            Utilisateurs
        </a>

        {{-- ✅ Traiteur --}}
        <a href="{{ route('admin.catering.index') }}"
           class="flex items-center justify-between px-4 py-3 rounded-xl transition
           {{ request()->routeIs('admin.catering.*') ? 'bg-orange-500 text-white shadow-lg' : 'hover:bg-gray-800' }}">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Traiteur
            </div>
            @php $newCatering = \App\Models\CateringRequest::where('status', 'nouvelle')->count(); @endphp
            @if($newCatering > 0)
                <span class="bg-blue-500 text-white text-xs px-2 py-0.5 rounded-full font-bold">
                    {{ $newCatering }}
                </span>
            @endif
        </a>

    </nav>

    {{-- Indicateur de connexion live --}}


    {{-- FOOTER --}}
    <div class="p-4 border-t border-gray-800">
        <div class="mb-4 text-xs text-gray-500">
            Connecté en tant que
            <div class="font-semibold text-white">{{ auth()->user()->name }}</div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full bg-red-500 py-2 rounded-lg hover:bg-red-600 transition text-sm font-medium text-white">
                Déconnexion
            </button>
        </form>
    </div>

</aside>
