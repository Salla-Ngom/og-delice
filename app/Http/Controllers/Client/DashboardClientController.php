<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardClientController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // ✅ Eager loading avec items — évite N+1 sur la vue
        $orders = $user->orders()
            ->with('items.product')
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'total_orders'   => $user->orders()->count(),
            // ✅ Statuts corrigés — 'pending' et 'completed' n'existent pas dans ce projet
            'pending_orders' => $user->orders()->enAttente()->count(),
            'total_spent'    => $user->orders()->prete()->sum('total_price'),
        ];

        return view('client.clientPage', compact('user', 'orders', 'stats'));
    }

    public function orders()
    {
        $user = auth()->user();

        // ✅ Pagination — ne jamais faire ->get() sur une liste sans limite
        $orders = $user->orders()
            ->with('items.product')
            ->latest()
            ->paginate(10);

        return view('client.orders', compact('orders'));
    }

    public function show(Order $order)
    {
        // ✅ Vérification d'appartenance — un client ne peut pas voir la commande d'un autre
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.product');

        return view('client.show', compact('order'));
    }
}
