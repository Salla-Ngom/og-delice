<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>O'G Délice – Accueil</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800">

    {{-- NAVBAR --}}
    @include('menusPage.navBar')

    {{-- HERO SECTION PRO --}}
    <section class="bg-gradient-to-r from-orange-500 to-red-600 text-white pt-32">
        <div class="max-w-7xl mx-auto px-6 py-24 grid md:grid-cols-2 gap-12 items-center">
            <div>
                <span class="uppercase tracking-wide text-sm text-yellow-200">Fast-Food • Traiteur</span>
                <h1 class="mt-4 text-4xl md:text-5xl font-extrabold leading-tight">
                    Le goût qui rassemble,
                    <span class="block text-yellow-300">la qualité qui fidélise</span>
                </h1>
                <p class="mt-6 text-lg text-orange-100 max-w-xl">
                    Commandez vos plats préférés chez <strong>O'G Délice</strong> : burgers, plats chauds et services traiteur pour tous vos événements.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="#menu" class="bg-white text-orange-600 font-semibold px-8 py-4 rounded-xl shadow hover:bg-orange-100 transition">
                        Voir le menu
                    </a>
                    <a href="#" class="border border-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-orange-600 transition">
                        Se connecter
                    </a>
                </div>
            </div>
            <div class="hidden md:block">
                <img src="https://images.unsplash.com/photo-1550547660-d9450f859349" alt="Burger" class="rounded-3xl shadow-2xl">
            </div>
        </div>
    </section>

    <section id="specialite" class="py-24 bg-gray-100">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-12 text-orange-600">
            Nos Spécialités
        </h2>

        <div class="grid md:grid-cols-3 gap-10">

            <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition">
                <h3 class="text-xl font-semibold mb-4">Thiéboudienne</h3>
                <p class="text-gray-600">
                    Le plat national sénégalais préparé avec passion.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition">
                <h3 class="text-xl font-semibold mb-4">Thiébou Guinar</h3>
                <p class="text-gray-600">
                    Riz savoureux accompagné de poulet braisé.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition">
                <h3 class="text-xl font-semibold mb-4">Jus de Bissap</h3>
                <p class="text-gray-600">
                    Boisson fraîche naturelle aux saveurs authentiques.
                </p>
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

            {{-- CATEGORIES --}}
            @foreach($categories as $category)
                <div class="mb-16">
                    <h3 class="text-2xl font-semibold mb-8 text-orange-600">{{ $category->name }}</h3>

                    <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                        @foreach($category->products as $product)
                            <div class="bg-gray-50 rounded-2xl shadow hover:shadow-lg transition overflow-hidden">
                                <img src="{{ asset('storage/'.$product->image)}}"
                                     alt="{{ $product->name }}"
                                     class="h-40 w-full object-cover">
                                <div class="p-5">
                                    <h4 class="font-semibold text-lg">{{ $product->name }}</h4>
                                    <p class="text-sm text-gray-500 mt-1 line-clamp-2">
                                        {{ $product->description }}
                                    </p>
                                    <div class="mt-4 flex items-center justify-between">
                                        <span class="font-bold text-orange-600">
                                            {{ number_format($product->price, 0, ',', ' ') }} FCFA
                                        </span>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-20 bg-gray-900 text-white text-center">
        <h2 class="text-3xl font-bold mb-6">Un événement à organiser ?</h2>
        <p class="text-gray-300 mb-8">Faites confiance à notre service traiteur professionnel.</p>
        <a href="#" class="bg-orange-500 px-8 py-4 rounded-xl font-semibold hover:bg-orange-600 transition">
            Réserver un traiteur
        </a>
    </section>

    {{-- FOOTER --}}
    <footer class="bg-gray-800 text-gray-300 py-6 text-center">
        <p>© {{ date('Y') }} O'G Délice. Tous droits réservés.</p>
    </footer>

</body>
</html>
