<nav class="fixed top-0 left-0 w-full bg-white shadow-md z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

        {{-- LOGO --}}

         <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold">O'G</span>
                </div>
                <div class="leading-tight">
                    <span class="text-xl font-bold text-pink-800 block">O’G Délice</span>
                    <span class="text-sm text-gray-500 block">
                        Restaurant • Fast-Food • Traiteur
                    </span>
                </div>
            </div>

        {{-- MENU --}}
        <div class="hidden md:flex items-center gap-8 font-medium">

            <a href="/" 
               class="hover:text-orange-600 transition {{ request()->is('/') ? 'text-orange-600 font-semibold' : '' }}">
                Accueil
            </a>

            <a href="#menu" 
               class="hover:text-orange-600 transition">
                Menu
            </a>

            <a href="#specialite" 
               class="hover:text-orange-600 transition">
                Spécialité
            </a>

            <a href="{{ route('login') }}" 
               class="bg-orange-500 text-white px-5 py-2 rounded-lg hover:bg-orange-600 transition shadow">
                Se connecter
            </a>

        </div>

        {{-- MOBILE BUTTON --}}
        <div class="md:hidden">
            <button onclick="document.getElementById('mobileMenu').classList.toggle('hidden')">
                ☰
            </button>
        </div>
    </div>

    {{-- MOBILE MENU --}}
    <div id="mobileMenu" class="hidden md:hidden bg-white border-t">
        <div class="flex flex-col p-4 gap-4">

            <a href="/" class="hover:text-orange-600">Accueil</a>
            <a href="#menu" class="hover:text-orange-600">Menu</a>
            <a href="#specialite" class="hover:text-orange-600">Spécialité</a>
            <a href="{{ route('login') }}" class="bg-orange-500 text-white px-4 py-2 rounded-lg text-center">
                Se connecter
            </a>

        </div>
    </div>
</nav>

