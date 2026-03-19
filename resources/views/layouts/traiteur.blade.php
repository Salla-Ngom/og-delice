<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- ✅ CSRF meta — requis pour fetch() JS (ajout panier, etc.) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'O\'G Délice') – Restaurant • Fast-Food • Traiteur</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-gray-50 text-gray-800 pt-24">

      @include('menusPage.navBar')

    @yield('content')

    <footer class="bg-gray-800 text-gray-300 py-6 text-center">
        <p>© {{ date('Y') }} O'G Délice. Tous droits réservés.</p>
    </footer>
</body>
</html>
