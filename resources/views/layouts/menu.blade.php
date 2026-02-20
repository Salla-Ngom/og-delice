<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>O'G Délice</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-50 text-gray-800 pt-24">

    @include('menusPage.navBarClient')

    @yield('content')

    <footer class="bg-gray-800 text-gray-300 py-6 text-center">
        <p>© {{ date('Y') }} O'G Délice. Tous droits réservés.</p>
    </footer>
    <script>
        function menuComponent() {
            return {
                showToast: false,

                addToCart(id) {

                    fetch(`/cart/add/${id}`, {
                            method: "POST",
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {

                            this.showToast = true

                            setTimeout(() => {
                                this.showToast = false
                            }, 2000)

                            // Met à jour compteur panier
                            document.querySelector('[x-data="cartComponent()"]').__x.$data.cartCount = data.cartCount
                        })
                }
            }
        }
    </script>
</body>

</html>
