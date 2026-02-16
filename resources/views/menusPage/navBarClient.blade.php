<nav id="navbar" class="fixed top-0 left-0 w-full bg-transparent transition-all duration-300 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

        {{-- LOGO --}}
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center">
                <span class="text-white font-bold">O'G</span>
            </div>
            <div class="leading-tight">
                <span class="text-xl font-bold text-white block nav-text">O’G Délice</span>
                <span class="text-sm text-gray-200 block nav-text">
                    Restaurant • Fast-Food • Traiteur
                </span>
            </div>
        </div>

        {{-- MENU --}}
        <div class="hidden md:flex items-center gap-8 font-medium">

            <a href="/" class="nav-link">Accueil</a>
            <a href="#menu" class="nav-link">Menu</a>
            <a href="#specialite" class="nav-link">Spécialité</a>

            {{-- PANIER --}}
            <a href="{{ route('cart.index') }}" class="relative nav-link">
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
                    <button class="bg-orange-500 text-white px-5 py-2 rounded-lg shadow">
                        {{ auth()->user()->name }}
                    </button>

                    <div class="absolute hidden group-hover:block bg-white shadow-lg rounded-lg mt-2 w-40">
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Profil</a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="w-full text-left px-4 py-2 hover:bg-gray-100">
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
        <div class="md:hidden text-white text-2xl cursor-pointer"
             onclick="document.getElementById('mobileMenu').classList.toggle('hidden')">
            ☰
        </div>
    </div>
</nav>
