<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') – O'G Délice</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-gray-100 text-gray-800">

    @include('menusPage.navBarAdmin')

    <main class="ml-64 min-h-screen p-8">

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show"
                 x-init="setTimeout(() => show = false, 4000)"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-y-2"
                 class="mb-6 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-5 py-3 rounded-xl shadow-sm">
                <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show"
                 x-init="setTimeout(() => show = false, 5000)"
                 class="mb-6 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-5 py-3 rounded-xl shadow-sm">
                <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-xl">
                <p class="font-semibold mb-2">Veuillez corriger les erreurs suivantes :</p>
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')

    {{-- ✅ POLLING TEMPS RÉEL — toutes les 30s, transparent pour l'admin --}}
    <script>
    (function() {
        const POLL_INTERVAL = 30000; // 30 secondes
        const POLL_URL      = '{{ route("admin.live.poll") }}';
        const CSRF          = document.querySelector('meta[name="csrf-token"]')?.content;

        // Éléments mis à jour sans rechargement
        const badgePending  = document.getElementById('badge-pending');
        const badgeNotifs   = document.getElementById('badge-notifs');
        const liveDot       = document.getElementById('live-dot');
        const liveLabel     = document.getElementById('live-label');

        // Container des commandes récentes sur le dashboard (null si pas sur dashboard)
        const recentOrdersContainer = document.getElementById('recent-orders-list');

        let previousPending = parseInt(badgePending?.textContent || '0');

        function updateBadge(el, count) {
            if (!el) return;
            if (count > 0) {
                el.textContent = count;
                el.classList.remove('hidden');
            } else {
                el.classList.add('hidden');
            }
        }

        function setLiveStatus(ok) {
            if (!liveDot || !liveLabel) return;
            if (ok) {
                liveDot.className  = 'w-2 h-2 rounded-full bg-green-400 animate-pulse inline-block';
                liveLabel.textContent = 'Synchronisation active';
            } else {
                liveDot.className  = 'w-2 h-2 rounded-full bg-red-400 inline-block';
                liveLabel.textContent = 'Hors ligne…';
            }
        }

        function showNewOrderToast(count) {
            // Toast discret — disparaît en 5s
            const toast = document.createElement('div');
            toast.className = [
                'fixed bottom-6 right-6 z-50',
                'bg-gray-900 text-white px-5 py-3 rounded-2xl shadow-2xl',
                'flex items-center gap-3 text-sm font-medium',
                'transition-all duration-500 opacity-0 translate-y-4'
            ].join(' ');
            toast.innerHTML = `
                <span class="text-orange-400 text-lg">🔔</span>
                <span>${count} nouvelle${count > 1 ? 's' : ''} commande${count > 1 ? 's' : ''} en attente</span>
                <a href="{{ route('admin.orders.index', ['status' => 'en_attente']) }}"
                   class="ml-2 text-orange-400 hover:text-orange-300 underline">Voir</a>
            `;
            document.body.appendChild(toast);
            // Apparition
            requestAnimationFrame(() => {
                toast.classList.remove('opacity-0', 'translate-y-4');
                toast.classList.add('opacity-100', 'translate-y-0');
            });
            // Disparition
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-4');
                setTimeout(() => toast.remove(), 500);
            }, 5000);
        }

        function updateRecentOrders(orders) {
            if (!recentOrdersContainer || !orders?.length) return;

            recentOrdersContainer.innerHTML = orders.map(o => `
                <a href="${o.url}"
                   class="flex justify-between items-center py-3 border-b last:border-0 hover:bg-gray-50 rounded-xl px-3 transition block">
                    <div>
                        <p class="font-semibold text-gray-800 text-sm">${o.user_name}</p>
                        <p class="text-xs text-gray-400">${o.time}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-orange-600 text-sm">${o.total}</p>
                        <span class="text-xs px-2 py-0.5 rounded-full ${o.status_badge}">${o.status_label}</span>
                    </div>
                </a>
            `).join('');
        }

        async function poll() {
            try {
                const res = await fetch(POLL_URL, {
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!res.ok) throw new Error('HTTP ' + res.status);

                const data = await res.json();

                // Mise à jour badges
                updateBadge(badgePending, data.pending_orders);
                updateBadge(badgeNotifs, data.unread_notifs);

                // Toast si nouvelles commandes depuis le dernier poll
                if (data.pending_orders > previousPending) {
                    showNewOrderToast(data.pending_orders);
                }
                previousPending = data.pending_orders;

                // Mise à jour liste commandes récentes (dashboard uniquement)
                updateRecentOrders(data.recent_orders);

                setLiveStatus(true);

            } catch (e) {
                console.warn('[OG Live] Polling failed:', e.message);
                setLiveStatus(false);
            }
        }

        // Premier poll après 5s (pas immédiatement — la page vient de charger)
        setTimeout(poll, 5000);

        // Puis toutes les 30s
        setInterval(poll, POLL_INTERVAL);

        // Poll immédiat quand l'admin revient sur l'onglet
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') poll();
        });

    })();
    </script>

</body>
</html>
