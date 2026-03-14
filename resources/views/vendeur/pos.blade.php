@extends('layouts.vendeur')

@section('title', 'Caisse — Point de vente')

@section('content')
<div class="h-[calc(100vh-4rem)] flex overflow-hidden bg-gray-100" x-data="pos()">

    {{-- ══════════ PANNEAU GAUCHE : Catalogue produits ══════════ --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Barre recherche + filtres catégories --}}
        <div class="bg-white border-b px-4 py-3 space-y-2 shrink-0">
            <input type="text" x-model="search" placeholder="🔍 Rechercher un produit..."
                   class="w-full border rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none">

            <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide">
                <button @click="activeCategory = null"
                        :class="activeCategory === null ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-600'"
                        class="px-3 py-1.5 rounded-full text-xs font-medium whitespace-nowrap transition">
                    Tout
                </button>
                @foreach($categories as $cat)
                    <button @click="activeCategory = {{ $cat->id }}"
                            :class="activeCategory === {{ $cat->id }} ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-600'"
                            class="px-3 py-1.5 rounded-full text-xs font-medium whitespace-nowrap transition">
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Grille produits --}}
        <div class="flex-1 overflow-y-auto p-4">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach($products as $product)
                    <div x-show="matchProduct({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->category_id ?? 'null' }})"
                         @click="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->final_price }}, '{{ $product->image_url }}', {{ $product->stock }})"
                         class="bg-white rounded-2xl shadow-sm border hover:border-orange-400 hover:shadow-md transition cursor-pointer active:scale-95 select-none p-3 flex flex-col">

                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                             class="w-full h-24 object-cover rounded-xl mb-2">

                        <p class="text-sm font-semibold text-gray-800 leading-tight line-clamp-2 flex-1">
                            {{ $product->name }}
                        </p>

                        <div class="mt-2 flex items-center justify-between">
                            <span class="text-orange-600 font-bold text-sm">
                                {{ number_format($product->final_price, 0, ',', ' ') }}
                                <span class="text-xs font-normal">FCFA</span>
                            </span>
                            <span class="text-xs text-gray-400">Stock: {{ $product->stock }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <p x-show="filteredCount === 0" class="text-center text-gray-400 py-16 text-sm">
                Aucun produit trouvé.
            </p>
        </div>
    </div>

    {{-- ══════════ PANNEAU DROIT : Panier / Caisse ══════════ --}}
    <div class="w-96 bg-white border-l flex flex-col shadow-xl shrink-0">

        {{-- En-tête panier --}}
        <div class="px-5 py-4 border-b flex items-center justify-between bg-orange-500 text-white">
            <div>
                <h2 class="font-bold text-lg">Caisse</h2>
                <p class="text-orange-100 text-xs">{{ auth()->user()->name }}</p>
            </div>
            <span class="bg-white text-orange-600 rounded-full w-8 h-8 flex items-center justify-center font-bold text-sm"
                  x-text="cart.length"></span>
        </div>

        {{-- Nom client optionnel --}}
        <div class="px-4 py-3 border-b bg-gray-50">
            <input type="text" x-model="customerName"
                   placeholder="👤 Nom du client (optionnel)"
                   class="w-full border rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none bg-white">
        </div>

        {{-- Liste articles --}}
        <div class="flex-1 overflow-y-auto px-4 py-3 space-y-2">
            <template x-if="cart.length === 0">
                <div class="flex flex-col items-center justify-center h-full text-gray-300 py-12">
                    <p class="text-4xl mb-2">🛒</p>
                    <p class="text-sm">Cliquez sur un produit pour l'ajouter</p>
                </div>
            </template>

            <template x-for="(item, index) in cart" :key="item.id">
                <div class="flex items-center gap-3 bg-gray-50 rounded-xl p-3">
                    <img :src="item.image" class="w-12 h-12 object-cover rounded-lg shrink-0">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate" x-text="item.name"></p>
                        <p class="text-orange-600 text-xs font-bold" x-text="formatPrice(item.price) + ' FCFA'"></p>
                    </div>
                    <div class="flex items-center gap-1 shrink-0">
                        <button @click="decrement(index)"
                                class="w-7 h-7 rounded-full border text-gray-500 hover:border-orange-400 hover:text-orange-500 transition text-base font-bold flex items-center justify-center">
                            −
                        </button>
                        <span class="w-6 text-center text-sm font-bold" x-text="item.quantity"></span>
                        <button @click="increment(index)"
                                class="w-7 h-7 rounded-full border text-gray-500 hover:border-orange-400 hover:text-orange-500 transition text-base font-bold flex items-center justify-center">
                            +
                        </button>
                    </div>
                    <button @click="removeItem(index)" class="text-gray-300 hover:text-red-500 transition ml-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </template>
        </div>

        {{-- Note --}}
        <div class="px-4 pb-2" x-show="cart.length > 0">
            <textarea x-model="note" placeholder="📝 Note (optionnel)" rows="2"
                      class="w-full border rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none resize-none"></textarea>
        </div>

        {{-- Total + bouton encaisser --}}
        <div class="px-4 pb-5 pt-2 border-t space-y-3" x-show="cart.length > 0">
            <div class="flex justify-between items-center">
                <span class="text-gray-500 text-sm">Total articles</span>
                <span class="text-sm" x-text="totalItems + ' article' + (totalItems > 1 ? 's' : '')"></span>
            </div>
            <div class="flex justify-between items-center font-bold text-lg">
                <span>TOTAL</span>
                <span class="text-orange-600" x-text="formatPrice(total) + ' FCFA'"></span>
            </div>

            <button @click="encaisser()"
                    :disabled="loading || cart.length === 0"
                    class="w-full bg-orange-500 hover:bg-orange-600 disabled:opacity-50 text-white font-bold py-4 rounded-xl transition shadow-lg text-base flex items-center justify-center gap-2">
                <template x-if="!loading">
                    <span>💰 Encaisser</span>
                </template>
                <template x-if="loading">
                    <span class="flex items-center gap-2">
                        <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                        </svg>
                        Enregistrement...
                    </span>
                </template>
            </button>

            <button @click="clearCart()" class="w-full text-gray-400 hover:text-red-500 text-sm transition py-1">
                🗑 Vider le panier
            </button>
        </div>

        {{-- Modal succès + reçu --}}
        <div x-show="successOrder" x-transition
             class="absolute inset-0 bg-black/50 flex items-center justify-center z-50 p-6">
            <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-sm text-center space-y-4">
                <div class="text-5xl">✅</div>
                <h3 class="text-xl font-bold text-gray-800">Vente enregistrée !</h3>
                <p class="text-gray-500 text-sm" x-text="'Référence : ' + lastReference"></p>
                <div class="flex flex-col gap-3 pt-2">
                    <a :href="receiptUrl" target="_blank"
                       class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 rounded-xl transition flex items-center justify-center gap-2">
                        🖨️ Imprimer le reçu PDF
                    </a>
                    <button @click="newSale()"
                            class="border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold py-3 rounded-xl transition">
                        + Nouvelle vente
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function pos() {
    return {
        cart: [],
        search: '',
        activeCategory: null,
        customerName: '',
        note: '',
        loading: false,
        successOrder: false,
        lastReference: '',
        receiptUrl: '',
        filteredCount: {{ $products->count() }},

        get total() {
            return this.cart.reduce((s, i) => s + i.price * i.quantity, 0);
        },
        get totalItems() {
            return this.cart.reduce((s, i) => s + i.quantity, 0);
        },

        matchProduct(id, name, categoryId) {
            const searchOk = !this.search || name.toLowerCase().includes(this.search.toLowerCase());
            const catOk    = this.activeCategory === null || categoryId === this.activeCategory;
            return searchOk && catOk;
        },

        addToCart(id, name, price, image, stock) {
            const existing = this.cart.find(i => i.id === id);
            if (existing) {
                if (existing.quantity < stock) {
                    existing.quantity++;
                } else {
                    this.flash('Stock maximum atteint pour ' + name);
                }
            } else {
                this.cart.push({ id, name, price, image, stock, quantity: 1 });
            }
        },

        increment(index) {
            const item = this.cart[index];
            if (item.quantity < item.stock) {
                item.quantity++;
            } else {
                this.flash('Stock maximum atteint');
            }
        },

        decrement(index) {
            if (this.cart[index].quantity > 1) {
                this.cart[index].quantity--;
            } else {
                this.removeItem(index);
            }
        },

        removeItem(index) {
            this.cart.splice(index, 1);
        },

        clearCart() {
            if (confirm('Vider le panier ?')) {
                this.cart = [];
                this.customerName = '';
                this.note = '';
            }
        },

        formatPrice(n) {
            return new Intl.NumberFormat('fr-FR').format(n);
        },

        flash(msg) {
            // Toast simple
            const el = document.createElement('div');
            el.className = 'fixed bottom-6 left-1/2 -translate-x-1/2 bg-gray-800 text-white px-5 py-3 rounded-xl text-sm shadow-xl z-[9999] transition';
            el.textContent = msg;
            document.body.appendChild(el);
            setTimeout(() => el.remove(), 2500);
        },

        async encaisser() {
            if (this.cart.length === 0) return;
            this.loading = true;

            try {
                const res = await fetch('{{ route('vendeur.pos.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        items: this.cart.map(i => ({ id: i.id, quantity: i.quantity })),
                        customer_name: this.customerName,
                        note: this.note,
                    }),
                });

                const data = await res.json();

                if (data.success) {
                    this.lastReference = data.reference;
                    this.receiptUrl    = data.receipt_url;
                    this.successOrder  = true;
                } else {
                    this.flash(data.message || 'Erreur lors de l\'enregistrement.');
                }
            } catch (e) {
                this.flash('Erreur réseau. Réessayez.');
            } finally {
                this.loading = false;
            }
        },

        newSale() {
            this.cart          = [];
            this.customerName  = '';
            this.note          = '';
            this.successOrder  = false;
            this.lastReference = '';
            this.receiptUrl    = '';
        },
    };
}
</script>
@endsection
