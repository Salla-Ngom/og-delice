<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class OrderController extends Controller
{


public function store()
{
    $cart = session()->get('cart', []);

    if (empty($cart)) {
        return redirect()->back()->with('error', 'Panier vide');
    }

    DB::beginTransaction();

    try {

        $total = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $order = Order::create([
            'user_id' => auth()->id(),
            'total_price' => $total,
            'status' => 'pending'
        ]);

        foreach ($cart as $id => $item) {

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        DB::commit();

        session()->forget('cart');

        return redirect()->route('client.dashboard')
            ->with('success', 'Commande enregistrée avec succès 🎉');

    } catch (\Exception $e) {

        DB::rollBack();
        return redirect()->back()->with('error', 'Erreur lors de la commande');

    }
}
}
