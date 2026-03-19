<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session expirée — O'G Délice</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-12px)} }
        .float { animation: float 3s ease-in-out infinite; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-yellow-50 via-white to-gray-50 flex items-center justify-center px-6">
    <div class="text-center max-w-md">
        <div class="float text-8xl mb-6 select-none">⏰</div>
        <p class="text-yellow-200 font-black text-8xl leading-none mb-2 select-none">419</p>
        <h1 class="text-2xl font-bold text-gray-800 mb-3">Session expirée</h1>
        <p class="text-gray-500 text-sm leading-relaxed mb-8">
            Votre session a expiré ou le formulaire a été soumis deux fois.<br>
            Retournez en arrière et réessayez.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="javascript:history.back()"
               class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-3 rounded-xl transition shadow">
                ← Retour
            </a>
            <a href="{{ url('/') }}"
               class="border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold px-6 py-3 rounded-xl transition">
                Accueil
            </a>
        </div>
        <div class="mt-12 flex items-center justify-center gap-2 text-gray-400">
            <div class="w-7 h-7 bg-orange-500 rounded-lg flex items-center justify-center shadow">
                <span class="text-white font-bold text-xs">O'G</span>
            </div>
            <span class="text-sm font-medium">O'G Délice</span>
        </div>
    </div>
</body>
</html>
