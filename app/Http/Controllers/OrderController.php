<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $cart = session()->get('cart', []);

        // Panier vide
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Votre panier est vide.');
        }

        // Récupérer les produits réels depuis la DB — ne jamais faire confiance aux prix en session
        $productIds = array_keys($cart);
        $products   = Product::whereIn('id', $productIds)
            ->where('is_active', true)
            ->get()
            ->keyBy('id');

        // Vérifier que tous les produits existent et sont actifs
        foreach ($productIds as $id) {
            if (!$products->has($id)) {
                return redirect()->back()
                    ->with('error', 'Un produit de votre panier n\'est plus disponible.');
            }
        }

        DB::transaction(function () use ($cart, $products) {

            // Calcul du total depuis les VRAIS prix DB (pas ceux de la session)
            $total = 0;
            foreach ($cart as $id => $item) {
                $product = $products->get($id);
                $total  += $product->final_price * $item['quantity'];
            }

            // Créer la commande — user_id assigné explicitement
            $order = Order::create(['total_price' => $total]);
            $order->user_id = auth()->id();
            $order->status  = 'en_attente';
            $order->save();

            // Créer les lignes avec snapshot des prix
            foreach ($cart as $id => $item) {
                $product = $products->get($id);

                OrderItem::create([
                    'order_id'        => $order->id,
                    'product_id'      => $id,
                    'quantity'        => $item['quantity'],
                    'unit_price'      => $product->price,           // snapshot prix normal
                    'unit_price_promo'=> $product->discount_price,  // snapshot promo (null si aucune)
                ]);

                // Décrémenter le stock
                $product->decrement('stock', $item['quantity']);
            }

            // Notifier les admins — hors boucle de création des items
            User::where('role', 'admin')->get()
                ->each(fn($admin) => $admin->notify(new NewOrderNotification($order)));

            // Vider le panier après succès
            session()->forget('cart');
        });

        return redirect()
            ->route('client.dashboard')
            ->with('success', 'Commande passée avec succès !');
    }
}
