<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accès refusé — O'G Délice</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-12px); }
        }
        .float { animation: float 3s ease-in-out infinite; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-red-50 flex items-center justify-center px-6">

    <div class="text-center max-w-md">

        {{-- Icône animée --}}
        <div class="float text-8xl mb-6 select-none">🔒</div>

        {{-- Code erreur --}}
        <p class="text-orange-300 font-black text-8xl leading-none mb-2 select-none">403</p>

        {{-- Titre --}}
        <h1 class="text-2xl font-bold text-gray-800 mb-3">
            Accès refusé
        </h1>

        {{-- Message --}}
        <p class="text-gray-500 text-sm leading-relaxed mb-8">
            Vous n'avez pas la permission d'accéder à cette page.<br>
            Si vous pensez qu'il s'agit d'une erreur, contactez l'administrateur.
        </p>

        {{-- Message personnalisé si fourni par abort(403, '...') --}}
        @if($exception->getMessage() && $exception->getMessage() !== 'This action is unauthorized.')
            <div class="bg-red-50 border border-red-100 rounded-xl px-4 py-3 text-sm text-red-600 mb-6">
                {{ $exception->getMessage() }}
            </div>
        @endif

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : '/' }}"
               class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-3 rounded-xl transition shadow">
                ← Retour
            </a>

            @auth
                @php
                    $home = match(auth()->user()->role) {
                        'admin'   => route('admin.dashboard'),
                        'vendeur' => route('vendeur.pos'),
                        default   => route('client.dashboard'),
                    };
                @endphp
                <a href="{{ $home }}"
                   class="border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold px-6 py-3 rounded-xl transition">
                    Mon tableau de bord
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold px-6 py-3 rounded-xl transition">
                    Se connecter
                </a>
            @endauth
        </div>

        {{-- Branding --}}
        <div class="mt-12 flex items-center justify-center gap-2 text-gray-400">
            <div class="w-7 h-7 bg-orange-500 rounded-lg flex items-center justify-center shadow">
                <span class="text-white font-bold text-xs">O'G</span>
            </div>
            <span class="text-sm font-medium">O'G Délice</span>
        </div>

    </div>

</body>
</html>
