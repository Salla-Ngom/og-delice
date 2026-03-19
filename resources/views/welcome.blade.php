<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="O'G Délice — Restaurant, Fast-Food et Traiteur à Dakar. Commandez en ligne vos plats préférés.">
    <title>O'G Délice – Restaurant • Fast-Food • Traiteur</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800">

    @include('menusPage.navBar')

    {{-- HERO --}}
    <section class="bg-gradient-to-r from-orange-500 to-red-600 text-white pt-32">
        <div class="max-w-7xl mx-auto px-6 py-24 grid md:grid-cols-2 gap-12 items-center">
            <div>
                <span class="uppercase tracking-wide text-sm text-yellow-200">Fast-Food • Traiteur</span>
                <h1 class="mt-4 text-4xl md:text-5xl font-extrabold leading-tight">
                    Le goût qui rassemble,
                    <span class="block text-yellow-300">la qualité qui fidélise</span>
                </h1>
                <p class="mt-6 text-lg text-orange-100 max-w-xl">
                    Commandez vos plats préférés chez <strong>O'G Délice</strong> :
                    burgers, plats chauds et services traiteur pour tous vos événements.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('menu') }}"
                       class="bg-white text-orange-600 font-semibold px-8 py-4 rounded-xl shadow hover:bg-orange-100 transition">
                        Voir le menu
                    </a>
                    {{-- ✅ route login au lieu de # --}}
                    @guest
                        <a href="{{ route('login') }}"
                           class="border border-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-orange-600 transition">
                            Se connecter
                        </a>
                    @else
                        <a href="{{ route('client.dashboard') }}"
                           class="border border-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-orange-600 transition">
                            Mon espace
                        </a>
                    @endguest
                </div>
            </div>
            {{-- ✅ Image locale au lieu d'Unsplash — crée public/images/hero-burger.jpg
                 ou utilise un produit existant en DB --}}
            <div class="hidden md:flex items-center justify-center">
                @if($featuredImage ?? false)
                    <img src="{{ $featuredImage }}" alt="Plat O'G Délice"
                         class="rounded-3xl shadow-2xl max-h-96 object-cover w-full">
                @else
                    <div class="w-full h-72 bg-white/20 rounded-3xl flex items-center justify-center">
                        <span class="text-8xl">🍽️</span>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- SPÉCIALITÉS --}}
    <section class="py-24 bg-gray-100">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-12 text-orange-600">Nos Spécialités</h2>
            <div class="grid md:grid-cols-3 gap-10">
                <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition">
                    <span class="text-4xl block mb-4">🍚</span>
                    <h3 class="text-xl font-semibold mb-2">Thiéboudienne</h3>
                    <p class="text-gray-600">Le plat national sénégalais préparé avec passion.</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition">
                    <span class="text-4xl block mb-4">🍗</span>
                    <h3 class="text-xl font-semibold mb-2">Thiébou Guinar</h3>
                    <p class="text-gray-600">Riz savoureux accompagné de poulet braisé.</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition">
                    <span class="text-4xl block mb-4">🥤</span>
                    <h3 class="text-xl font-semibold mb-2">Jus de Bissap</h3>
                    <p class="text-gray-600">Boisson fraîche naturelle aux saveurs authentiques.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- MENU DYNAMIQUE --}}
    <section id="menu" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold">Notre Menu</h2>
                <p class="mt-4 text-gray-600">Découvrez nos plats soigneusement préparés</p>
            </div>

            @forelse($categories as $category)
                <div class="mb-16">
                    <h3 class="text-2xl font-semibold mb-8 text-orange-600">{{ $category->name }}</h3>
                    <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                        @foreach($category->products as $product)
                            <div class="bg-gray-50 rounded-2xl shadow hover:shadow-lg transition overflow-hidden">
                                {{-- ✅ image_url accessor — fallback si null --}}
                                <img src="{{ $product->image_url }}"
                                     alt="{{ $product->name }}"
                                     class="h-40 w-full object-cover"
                                     loading="lazy">
                                <div class="p-5">
                                    <h4 class="font-semibold text-lg">{{ $product->name }}</h4>
                                    <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $product->description }}</p>
                                    <div class="mt-4 flex items-center justify-between">
                                        <span class="font-bold text-orange-600">
                                            {{ number_format($product->final_price, 0, ',', ' ') }} FCFA
                                        </span>
                                        <a href="{{ route('menu') }}"
                                           class="text-xs text-orange-500 hover:underline font-medium">
                                            Commander →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-400 py-12">Aucun produit disponible pour le moment.</p>
            @endforelse
        </div>
    </section>

    {{-- CTA TRAITEUR --}}
    <section class="py-20 bg-gray-900 text-white text-center">
        <h2 class="text-3xl font-bold mb-6">Un événement à organiser ?</h2>
        <p class="text-gray-300 mb-8">Faites confiance à notre service traiteur professionnel.</p>
        <a href="{{ route('traiteur.create') }}"
           class="bg-orange-500 px-8 py-4 rounded-xl font-semibold hover:bg-orange-600 transition">
            Demander un devis
        </a>
    </section>

    <footer class="bg-gray-800 text-gray-300 py-6 text-center">
        <p>© {{ date('Y') }} O'G Délice. Tous droits réservés.</p>
    </footer>

</body>
</html>
