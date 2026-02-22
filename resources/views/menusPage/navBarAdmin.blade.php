<aside class="fixed inset-y-0 left-0 w-64 bg-gray-900 text-white shadow-xl flex flex-col">

    <div class="p-6 text-2xl font-bold border-b border-gray-700">
        O'G Admin
    </div>

    <nav class="flex-1 p-4 space-y-2">

        <a href="{{ route('admin.dashboard') }}"
           class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition">
            📊 Dashboard
        </a>

        <a href="{{ route('admin.products.index') }}"
           class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition">
            🍔 Produits
        </a>

        <a href="{{ route('admin.orders.index') }}"
           class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition">
            🧾 Commandes
        </a>

    </nav>

    <div class="p-4 border-t border-gray-700">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full bg-red-500 py-2 rounded-lg hover:bg-red-600 transition">
                Déconnexion
            </button>
        </form>
    </div>

</aside>