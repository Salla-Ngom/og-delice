<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>O'G Délice – Connexion Premium</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>
        .premium-blur {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">

    {{-- NAVBAR --}}
    @include('menusPage.navBar')

    {{-- SECTION LOGIN PREMIUM --}}
    <section class="flex-grow bg-gradient-to-br from-orange-500 via-red-500 to-red-600 flex items-center justify-center relative overflow-hidden mt-10 py-4 ">
        
        {{-- ÉLÉMENTS DÉCORATIFS PREMIUM --}}
        <div class="absolute inset-0">
            <div class="absolute top-0 -left-4 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
            <div class="absolute top-0 -right-4 w-72 h-72 bg-yellow-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 w-full relative z-10">
            
            {{-- CARTE DE CONNEXION PREMIUM --}}
            <div class="w-full max-w-md mx-auto">
                
                {{-- CARTE AVEC EFFET VERRE DÉPOLI --}}
                <div class="premium-blur rounded-3xl shadow-2xl p-8 md:p-10 border border-white/20">
                    
                    {{-- LOGO AVEC ANIMATION --}}
                    <div class="text-center mb-8">
                        <div class="w-24 h-24 mx-auto bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center shadow-xl transform hover:scale-105 transition duration-300">
                            <span class="text-white font-bold text-3xl">O'G</span>
                        </div>
                        <h2 class="mt-6 text-3xl font-bold text-gray-800">
                            Content de vous revoir
                        </h2>
                        <p class="text-gray-500 text-sm mt-2">
                            Connectez-vous pour accéder à votre espace personnel
                        </p>
                    </div>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <!-- Email avec design premium -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">
                                Adresse email
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-orange-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       required autofocus
                                       placeholder="nom@exemple.com"
                                       class="w-full pl-10 pr-4 py-4 rounded-xl border-2 border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 focus:outline-none transition bg-white/50 backdrop-blur-sm">
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password avec design premium -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">
                                Mot de passe
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-orange-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input type="password"
                                       name="password"
                                       required
                                       placeholder="••••••••"
                                       class="w-full pl-10 pr-4 py-4 rounded-xl border-2 border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 focus:outline-none transition bg-white/50 backdrop-blur-sm">
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Remember & Forgot avec design premium -->
                        <div class="flex items-center justify-between text-sm">
                            <label class="flex items-center space-x-2 cursor-pointer group">
                                <input type="checkbox" name="remember" class="rounded border-gray-300 text-orange-600 focus:ring-orange-500 w-4 h-4 transition">
                                <span class="text-gray-600 group-hover:text-gray-800">Se souvenir de moi</span>
                            </label>

                           @if (request()->routeIs('password.request') || \Illuminate\Support\Facades\Route::has('password.request'))

                                <a href="{{ route('password.request') }}"
                                   class="text-orange-600 hover:text-orange-700 font-medium transition hover:underline">
                                    Mot de passe oublié ?
                                </a>
                            @endif
                        </div>

                        <!-- Bouton premium avec animation -->
                        <button type="submit"
                                class="w-full bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 text-white font-bold py-4 rounded-xl shadow-lg transition duration-300 transform hover:scale-[1.02] hover:shadow-xl relative overflow-hidden group">
                            <span class="relative z-10">Se connecter</span>
                            <div class="absolute inset-0 bg-white transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left opacity-20"></div>
                        </button>

                        <!-- Lien d'inscription premium -->
                        <p class="text-center text-sm text-gray-600 pt-4">
                            Pas encore de compte ?
                            <a href="{{ route('register') }}" class="text-orange-600 font-bold hover:text-orange-700 transition hover:underline">
                                Créer un compte gratuitement
                            </a>
                        </p>

                        {{-- SÉPARATEUR ÉLÉGANT --}}
                        <div class="relative my-6">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-4 bg-transparent text-gray-500">Ou continuez avec</span>
                            </div>
                        </div>

                        {{-- BOUTONS SOCIAUX PREMIUM --}}
                        <div class="grid grid-cols-2 gap-4">
                            <button type="button" class="flex items-center justify-center space-x-2 py-3 px-4 border-2 border-gray-200 rounded-xl hover:border-orange-500 hover:bg-orange-50 transition group">
                                <svg class="w-5 h-5 text-gray-700 group-hover:text-orange-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-orange-600">Google</span>
                            </button>
                            <button type="button" class="flex items-center justify-center space-x-2 py-3 px-4 border-2 border-gray-200 rounded-xl hover:border-orange-500 hover:bg-orange-50 transition group">
                                <svg class="w-5 h-5 text-gray-700 group-hover:text-orange-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-orange-600">Facebook</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- FOOTER ÉLÉGANT --}}
    <footer class="bg-gray-900 text-gray-400 py-4 text-center text-sm border-t border-gray-800">
        <p>© {{ date('Y') }} O'G Délice. Tous droits réservés. | Design Premium</p>
    </footer>

    {{-- STYLES D'ANIMATION --}}
    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>

</body>
</html>