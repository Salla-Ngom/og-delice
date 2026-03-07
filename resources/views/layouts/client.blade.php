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

    @include('menusPage.navBarClient')

    {{-- ✅ Messages flash globaux --}}
    @if(session('success') || session('error'))
        <div class="max-w-7xl mx-auto px-6 mt-4">
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show"
                     x-init="setTimeout(() => show = false, 4000)"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-5 py-3 rounded-xl shadow-sm mb-4">
                    <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show"
                     x-init="setTimeout(() => show = false, 5000)"
                     class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-5 py-3 rounded-xl shadow-sm mb-4">
                    <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif
        </div>
    @endif

    @yield('content')

    <footer class="bg-gray-800 text-gray-300 py-6 text-center">
        <p>© {{ date('Y') }} O'G Délice. Tous droits réservés.</p>
    </footer>

    {{-- ✅ Badge panier — compte les articles (quantités), pas les lignes --}}
    <script>
    // Exposé globalement pour la navbar et les boutons "Ajouter"
    function addToCart(productId, btn) {
        btn.disabled = true;
        const original = btn.textContent;
        btn.textContent = '...';

        fetch(`/cart/add/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // Mettre à jour tous les badges panier de la page
                document.querySelectorAll('.cart-badge').forEach(el => {
                    el.textContent = data.cartCount;
                    el.classList.toggle('hidden', data.cartCount === 0);
                });
                showToast('✅ ' + (data.message ?? 'Ajouté au panier !'));
                btn.textContent = '✓';
                setTimeout(() => { btn.textContent = original; btn.disabled = false; }, 2000);
            } else {
                showToast('❌ ' + (data.message ?? 'Stock insuffisant.'));
                btn.textContent = original;
                btn.disabled = false;
            }
        })
        .catch(() => {
            showToast('❌ Erreur réseau.');
            btn.textContent = original;
            btn.disabled = false;
        });
    }

    function showToast(message) {
        let toast = document.getElementById('toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'toast';
            toast.className = 'fixed bottom-6 right-6 z-50 bg-gray-900 text-white text-sm px-5 py-3 rounded-xl shadow-xl transition-all duration-300';
            document.body.appendChild(toast);
        }
        toast.textContent = message;
        toast.classList.remove('opacity-0');
        clearTimeout(window._toastTimer);
        window._toastTimer = setTimeout(() => toast.classList.add('opacity-0'), 3500);
    }
    </script>

    @stack('scripts')

</body>
</html>
