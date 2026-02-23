<nav x-data="{ open: false, mobile: false }"
    class="fixed top-0 left-0 w-full bg-white/90 backdrop-blur-md shadow-sm border-b z-50">

    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

        {{-- LOGO --}}
        <a href="{{ route('client.dashboard') }}" class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center shadow">
                <span class="text-white font-bold">O'G</span>
            </div>
            <div class="leading-tight">
                <span class="text-xl font-bold text-gray-800 block">
                    O’G Délice
                </span>
                <span class="text-sm text-gray-500 block">
                    Restaurant • Fast-Food • Traiteur
                </span>
            </div>
        </a>

        {{-- MENU DESKTOP --}}
        <div class="hidden md:flex items-center gap-8 font-medium text-gray-700">

            <a href="{{ route('client.dashboard') }}"
                class="relative hover:text-orange-500 transition after:absolute after:left-0 after:-bottom-1 after:w-0 after:h-[2px] after:bg-orange-500 after:transition-all hover:after:w-full">
                Tableau de bord
            </a>

            {{-- PANIER --}}
            <div class="relative">
                <a href="{{ route('cart.index') }}"
                    class="relative hover:text-orange-500 transition text-xl">
                    🛒

                    @if(session('cart') && count(session('cart')) > 0)
                        <span
                            class="absolute -top-2 -right-3 bg-red-500 text-white text-xs min-w-[20px] h-5 flex items-center justify-center rounded-full px-1">
                            {{ count(session('cart')) }}
                        </span>
                    @endif
                </a>
            </div>

            {{-- AUTH --}}
            @auth
                <div class="relative">

                    <button @click="open = !open"
                        class="bg-orange-500 text-white px-5 py-2 rounded-lg shadow hover:bg-orange-600 transition flex items-center gap-2">
                        {{ auth()->user()->name }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open"
                        @click.away="open = false"
                        x-transition
                        class="absolute right-0 mt-3 w-52 bg-white shadow-xl rounded-xl border overflow-hidden">

                        <a href="{{ route('profile.edit') }}"
                            class="block px-4 py-3 hover:bg-gray-100 transition">
                            Profil
                        </a>

                        <div class="border-t"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                class="w-full text-left px-4 py-3 hover:bg-gray-100 transition text-red-600">
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            @endauth

        </div>

        {{-- MOBILE BUTTON --}}
        <div class="md:hidden text-gray-800 text-2xl cursor-pointer"
            @click="mobile = !mobile">
            ☰
        </div>

    </div>

    {{-- MOBILE MENU --}}
    <div x-show="mobile"
        x-transition
        class="md:hidden fixed inset-0 bg-white z-40 p-8">

        <div class="flex justify-between items-center mb-8">
            <span class="text-xl font-bold text-orange-600">O’G Délice</span>
            <button @click="mobile = false" class="text-2xl">
                ✕
            </button>
        </div>

        <div class="space-y-6 text-lg text-gray-700">

            <a href="{{ route('client.dashboard') }}"
                class="block hover:text-orange-500">
                Tableau de bord
            </a>

            <a href="{{ route('cart.index') }}"
                class="block hover:text-orange-500">
                Panier
            </a>

            @auth
                <a href="{{ route('profile.edit') }}"
                    class="block hover:text-orange-500">
                    Profil
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="block text-red-600 mt-4">
                        Déconnexion
                    </button>
                </form>
            @endauth

        </div>
    </div>

</nav>
