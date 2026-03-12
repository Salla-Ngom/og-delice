<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>O'G Délice – Créer un compte</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>
        .premium-blur { backdrop-filter: blur(10px); background: rgba(255,255,255,0.95); }
        @keyframes blob {
            0%   { transform: translate(0px,0px) scale(1); }
            33%  { transform: translate(30px,-50px) scale(1.1); }
            66%  { transform: translate(-20px,20px) scale(0.9); }
            100% { transform: translate(0px,0px) scale(1); }
        }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">

    @include('menusPage.navBar')

    <section class="flex-grow bg-gradient-to-br from-orange-500 via-red-500 to-red-600 flex items-center justify-center relative overflow-hidden mt-10 py-8">

        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-0 -left-4 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
            <div class="absolute top-0 -right-4 w-72 h-72 bg-yellow-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full relative z-10">
            <div class="w-full max-w-lg mx-auto">
                <div class="premium-blur rounded-3xl shadow-2xl p-8 md:p-10 border border-white/20">

                    <div class="text-center mb-8">
                        <div class="w-20 h-20 mx-auto bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center shadow-xl transform hover:scale-105 transition duration-300">
                            <span class="text-white font-bold text-2xl">O'G</span>
                        </div>
                        <h2 class="mt-5 text-3xl font-bold text-gray-800">Créer un compte</h2>
                        <p class="text-gray-500 text-sm mt-2">Rejoignez O'G Délice et commandez en quelques clics</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf

                        {{-- NOM --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nom complet</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-orange-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <input type="text" name="name" value="{{ old('name') }}"
                                       required autofocus placeholder="Votre prénom et nom"
                                       class="w-full pl-10 pr-4 py-3.5 rounded-xl border-2 {{ $errors->has('name') ? 'border-red-400' : 'border-gray-200' }} focus:border-orange-500 focus:ring-2 focus:ring-orange-200 focus:outline-none transition bg-white/50">
                            </div>
                            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- EMAIL --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Adresse email</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-orange-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <input type="email" name="email" value="{{ old('email') }}"
                                       required placeholder="nom@exemple.com"
                                       class="w-full pl-10 pr-4 py-3.5 rounded-xl border-2 {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200' }} focus:border-orange-500 focus:ring-2 focus:ring-orange-200 focus:outline-none transition bg-white/50">
                            </div>
                            @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- TÉLÉPHONE --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                Téléphone mobile
                                <span class="font-normal text-gray-400">(Sénégal)</span>
                            </label>
                            <div class="relative group">
                                {{-- Indicatif fixe --}}
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <span class="text-gray-500 font-medium text-sm">🇸🇳 +221</span>
                                </div>
                                <input type="tel" name="phone" value="{{ old('phone') }}"
                                       required
                                       placeholder="77 123 45 67"
                                       maxlength="12"
                                       class="w-full pl-20 pr-4 py-3.5 rounded-xl border-2 {{ $errors->has('phone') ? 'border-red-400' : 'border-gray-200' }} focus:border-orange-500 focus:ring-2 focus:ring-orange-200 focus:outline-none transition bg-white/50"
                                       oninput="formatPhone(this)">
                            </div>
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @else
                                <p class="text-xs text-gray-400 mt-1">Commence par 70, 71, 72, 76, 77 ou 78</p>
                            @enderror
                        </div>

                        {{-- ADRESSE DE LIVRAISON --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                Adresse de livraison
                                <span class="font-normal text-gray-400">(optionnelle)</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute top-3.5 left-0 pl-3 flex items-start pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-orange-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <textarea name="delivery_address" rows="2"
                                          placeholder="Ex: Rue 10, Sacré-Cœur 3, Dakar"
                                          class="w-full pl-10 pr-4 py-3.5 rounded-xl border-2 {{ $errors->has('delivery_address') ? 'border-red-400' : 'border-gray-200' }} focus:border-orange-500 focus:ring-2 focus:ring-orange-200 focus:outline-none transition bg-white/50 resize-none">{{ old('delivery_address') }}</textarea>
                            </div>
                            @error('delivery_address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- MOT DE PASSE --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Mot de passe</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-orange-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <input type="password" name="password" id="password"
                                       required placeholder="Minimum 8 caractères"
                                       class="w-full pl-10 pr-10 py-3.5 rounded-xl border-2 {{ $errors->has('password') ? 'border-red-400' : 'border-gray-200' }} focus:border-orange-500 focus:ring-2 focus:ring-orange-200 focus:outline-none transition bg-white/50">
                                <button type="button" onclick="togglePassword('password', this)"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-orange-500 transition">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                            @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- CONFIRMATION --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Confirmer le mot de passe</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-orange-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                       required placeholder="Répétez votre mot de passe"
                                       class="w-full pl-10 pr-10 py-3.5 rounded-xl border-2 border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 focus:outline-none transition bg-white/50">
                                <button type="button" onclick="togglePassword('password_confirmation', this)"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-orange-500 transition">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- BOUTON --}}
                        <button type="submit"
                                class="w-full bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 text-white font-bold py-4 rounded-xl shadow-lg transition duration-300 transform hover:scale-[1.02] hover:shadow-xl relative overflow-hidden group mt-2">
                            <span class="relative z-10">Créer mon compte</span>
                            <div class="absolute inset-0 bg-white transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left opacity-20"></div>
                        </button>

                        <p class="text-center text-sm text-gray-600 pt-2">
                            Déjà un compte ?
                            <a href="{{ route('login') }}" class="text-orange-600 font-bold hover:text-orange-700 transition hover:underline">
                                Se connecter
                            </a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-gray-900 text-gray-400 py-4 text-center text-sm border-t border-gray-800">
        <p>© {{ date('Y') }} O'G Délice. Tous droits réservés.</p>
    </footer>

    <script>
    function togglePassword(fieldId, btn) {
        const input = document.getElementById(fieldId);
        input.type = input.type === 'password' ? 'text' : 'password';
        btn.classList.toggle('text-orange-500');
    }

    // Format auto : 77 → 77 1 → 77 1... → 77 123 45 67
    function formatPhone(input) {
        let v = input.value.replace(/\D/g, '').slice(0, 9);
        let formatted = v;
        if (v.length > 2) formatted = v.slice(0,2) + ' ' + v.slice(2);
        if (v.length > 5) formatted = v.slice(0,2) + ' ' + v.slice(2,5) + ' ' + v.slice(5);
        if (v.length > 7) formatted = v.slice(0,2) + ' ' + v.slice(2,5) + ' ' + v.slice(5,7) + ' ' + v.slice(7);
        input.value = formatted;
    }
    </script>

</body>
</html>