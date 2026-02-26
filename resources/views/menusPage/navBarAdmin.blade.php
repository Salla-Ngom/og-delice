<aside class="fixed inset-y-0 left-0 w-64 bg-gray-950 text-gray-300 shadow-2xl flex flex-col">

    {{-- LOGO --}}
    <div class="p-6 border-b border-gray-800">
        <h1 class="text-2xl font-bold text-white tracking-wide">
            O'G Admin
        </h1>
        <p class="text-xs text-gray-500 mt-1">
            Gestion Restaurant
        </p>
    </div>

    {{-- NAVIGATION --}}
    <nav class="flex-1 p-4 space-y-2 text-sm">

        {{-- DASHBOARD --}}
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition
           {{ request()->routeIs('admin.dashboard') ? 'bg-orange-500 text-white shadow-lg' : 'hover:bg-gray-800' }}">

            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 13h8V3H3v10zM13 21h8v-6h-8v6zM13 3v8h8V3h-8zM3 21h8v-4H3v4z"/>
            </svg>

            Dashboard
        </a>

        {{-- PRODUITS --}}
        <a href="{{ route('admin.products.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition
           {{ request()->routeIs('admin.products.*') ? 'bg-orange-500 text-white shadow-lg' : 'hover:bg-gray-800' }}">

            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M20 13V7a2 2 0 00-2-2h-3l-2-2-2 2H6a2 2 0 00-2 2v6"/>
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M4 13h16v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6z"/>
            </svg>

            Produits
        </a>

        {{-- COMMANDES --}}
        <a href="{{ route('admin.orders.index') }}"
           class="flex items-center justify-between px-4 py-3 rounded-xl transition
           {{ request()->routeIs('admin.orders.*') ? 'bg-orange-500 text-white shadow-lg' : 'hover:bg-gray-800' }}">

            <div class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 17v-6h13M9 7h13M5 7h.01M5 11h.01M5 15h.01"/>
                </svg>
                Commandes
            </div>

            {{-- BADGE NOTIFICATION --}}
            @php
                $count = auth()->user()->unreadNotifications->count();
            @endphp

            @if($count > 0)
                <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full animate-pulse">
                    {{ $count }}
                </span>
            @endif
        </a>

        {{-- USERS --}}
        <a href="{{ route('admin.users.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition
           {{ request()->routeIs('admin.users.*') ? 'bg-orange-500 text-white shadow-lg' : 'hover:bg-gray-800' }}">

            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>

            Utilisateurs
        </a>

    </nav>

    {{-- FOOTER --}}
    <div class="p-4 border-t border-gray-800">

        <div class="mb-4 text-xs text-gray-500">
            Connecté en tant que
            <div class="font-semibold text-white">
                {{ auth()->user()->name }}
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                class="w-full bg-red-500 py-2 rounded-lg hover:bg-red-600 transition text-sm font-medium">
                Déconnexion
            </button>
        </form>

    </div>

</aside>
