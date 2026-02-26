<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\NewOrderNotification;
class OrderController extends Controller
{

public function store(Request $request)
{
    $cart = session()->get('cart');

    if (!$cart || count($cart) == 0) {
        return redirect()->back()->with('error', 'Votre panier est vide');
    }

    $total = 0;

    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    // Création de la commande
    $order = Order::create([
        'user_id'    => auth()->id(),
        'total_price'=> $total,
        'status' => 'en_attente'
    ]);

    // Création des lignes de commande
    foreach ($cart as $id => $item) {
        OrderItem::create([
            'order_id'  => $order->id,
            'product_id'=> $id,
            'quantity'  => $item['quantity'],
            'price'     => $item['price']
        ]);
         // Création de la commande
    $order = Order::create([
        'user_id'    => auth()->id(),
        'total_price'=> $total,
        'status' => 'en_attente'
    ]);
    $admins = User::whereIn('role', ['admin'])->get();

foreach ($admins as $admin) {
    $admin->notify(new NewOrderNotification($order));
}
    }

    // Vider le panier
    session()->forget('cart');

    return redirect()
        ->route('client.dashboard')
        ->with('success', 'Commande passée avec succès 🎉');
}
}
