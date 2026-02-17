<nav id="navbar" class="fixed top-0 left-0 w-full bg-transparent transition-all duration-300 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

        {{-- LOGO --}}
        <a href="{{ url('/') }}" class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center shadow">
                <span class="text-white font-bold">O'G</span>
            </div>
            <div class="leading-tight">
                <span class="text-xl font-bold text-white block nav-text transition">
                    O’G Délice
                </span>
                <span class="text-sm text-gray-200 block nav-text transition">
                    Restaurant • Fast-Food • Traiteur
                </span>
            </div>
        </a>

        {{-- MENU DESKTOP --}}
        <div class="hidden md:flex items-center gap-8 font-medium">

            <a href="/" class="nav-link">Accueil</a>
            <a href="#menu" class="nav-link">Menu</a>
            <a href="#specialite" class="nav-link">Spécialité</a>

            {{-- PANIER --}}
            <a href="{{ route('cart.index') }}" class="relative nav-link text-xl">
                🛒
                @if(session('cart'))
                    <span class="absolute -top-2 -right-3 bg-red-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">
                        {{ count(session('cart')) }}
                    </span>
                @endif
            </a>

            {{-- AUTH --}}
            @auth
                <div class="relative group">
                    <button class="bg-orange-500 text-white px-5 py-2 rounded-lg shadow hover:bg-orange-600 transition">
                        {{ auth()->user()->name }}
                    </button>

                    <div class="absolute right-0 hidden group-hover:block bg-white shadow-xl rounded-xl mt-3 w-48 overflow-hidden">

                        <a href="{{ route('client.dashboard') }}"
                           class="block px-4 py-3 hover:bg-gray-100 transition">
                            Tableau de bord
                        </a>

                        <a href="{{ route('profile.edit') }}"
                           class="block px-4 py-3 hover:bg-gray-100 transition">
                            Profil
                        </a>

                        <div class="border-t"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="w-full text-left px-4 py-3 hover:bg-gray-100 transition text-red-600">
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}"
                   class="bg-orange-500 text-white px-5 py-2 rounded-lg shadow hover:bg-orange-600 transition">
                    Se connecter
                </a>
            @endauth

        </div>

        {{-- MOBILE BUTTON --}}
        <div class="md:hidden text-white text-2xl cursor-pointer nav-text"
             onclick="document.getElementById('mobileMenu').classList.toggle('hidden')">
            ☰
        </div>
    </div>
</nav>

{{-- MOBILE MENU --}}
<div id="mobileMenu" class="hidden fixed top-0 left-0 w-full h-full bg-white z-40 p-8">

    <div class="flex justify-between items-center mb-8">
        <span class="text-xl font-bold text-orange-600">O’G Délice</span>
        <button onclick="document.getElementById('mobileMenu').classList.add('hidden')">
            ✕
        </button>
    </div>

    <div class="space-y-6 text-lg">
        <a href="#menu" class="block">Menu</a>
        <a href="#specialite" class="block">Spécialité</a>
        <a href="{{ route('cart.index') }}" class="block">Panier</a>

        @auth
            <a href="{{ route('client.dashboard') }}" class="block">Tableau de bord</a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="block text-red-600 mt-4">Déconnexion</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="block text-orange-600 font-semibold">
                Se connecter
            </a>
        @endauth
    </div>

</div>
