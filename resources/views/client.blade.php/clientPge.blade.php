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
    @include('menusPage.navBarClient')


    {{-- FOOTER --}}
    <footer class="bg-gray-800 text-gray-300 py-6 text-center">
        <p>© {{ date('Y') }} O'G Délice. Tous droits réservés.</p>
    </footer>

</body>
</html>