<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>O'G Délice</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800 pt-24">

    @include('menusPage.navBarClient')

    @yield('content')

    <footer class="bg-gray-800 text-gray-300 py-6 text-center">
        <p>© {{ date('Y') }} O'G Délice. Tous droits réservés.</p>
    </footer>
<script>
function cartComponent() {
    return {
        cartCount: {{ session('cart') ? count(session('cart')) : 0 }},
        updateCount(count) {
            this.cartCount = count
        }
    }
}
</script>
</body>
</html>
