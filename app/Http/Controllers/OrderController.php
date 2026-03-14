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

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Votre panier est vide.');
        }

        $productIds = array_keys($cart);
        $products   = Product::whereIn('id', $productIds)
            ->where('is_active', true)
            ->get()
            ->keyBy('id');

        foreach ($productIds as $id) {
            if (!$products->has($id)) {
                return redirect()->back()
                    ->with('error', 'Un produit de votre panier n\'est plus disponible.');
            }
        }

        $order = DB::transaction(function () use ($cart, $products) {

            $total = 0;
            foreach ($cart as $id => $item) {
                $total += $products->get($id)->final_price * $item['quantity'];
            }

            $order              = new Order();
            $order->user_id     = auth()->id();
            $order->total_price = $total;
            $order->status      = 'en_attente';
            $order->source      = 'online';
            $order->save();

            foreach ($cart as $id => $item) {
                $product = $products->get($id);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stock insuffisant pour « {$product->name} ».");
                }

                OrderItem::create([
                    'order_id'         => $order->id,
                    'product_id'       => $id,
                    'quantity'         => $item['quantity'],
                    'unit_price'       => $product->final_price,
                    'unit_price_promo' => $product->discount_price,
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            User::where('role', 'admin')->get()
                ->each(fn($admin) => $admin->notify(new NewOrderNotification($order)));

            session()->forget('cart');

            return $order;
        });

        return redirect()
            ->route('client.orders.show', $order)
            ->with('success', 'Commande passée avec succès ! Nous préparons votre commande.');
    }
}
