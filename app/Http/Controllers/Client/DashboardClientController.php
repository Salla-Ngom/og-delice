<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardClientController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $orders = $user->orders()
            ->with('items.product')
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'total_orders'   => $user->orders()->count(),
            'pending_orders' => $user->orders()->enAttente()->count(),
            'total_spent'    => $user->orders()->prete()->sum('total_price'),
        ];

        return view('client.clientPage', compact('user', 'orders', 'stats'));
    }

    public function orders()
    {
        $orders = auth()->user()->orders()
            ->with('items.product')
            ->latest()
            ->paginate(10);

        return view('client.orders', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.product');

        return view('client.show', compact('order'));
    }

    // ✅ Endpoint JSON pour le polling statut dans show.blade.php
    // Appelé toutes les 20s — retourne uniquement le statut, pas toute la commande
    public function pollStatus(Order $order): JsonResponse
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Relire uniquement le statut depuis la DB (pas de cache — on veut le temps réel)
        $order->refresh();

        return response()->json([
            'status'       => $order->status,
            'status_label' => $order->status_label,
            'status_badge' => $order->status_badge,
        ]);
    }
}