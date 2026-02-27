<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        // Filtre par statut depuis l'URL : /admin/orders?status=en_attente
        $status = $request->string('status')->toString() ?: null;

        $orders = Order::with('user')   // ✅ eager loading — évite N+1
            ->byStatus($status)         // ✅ scope sécurisé du modèle
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', compact('orders', 'status'));
    }

    public function show(Order $order)
    {
        // ✅ Eager loading complet en une requête
        $order->load(['user', 'items.product']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:' . implode(',', Order::STATUSES)],
        ]);

        // ✅ Utiliser transitionTo() du modèle — sécurisé + invalide le cache
        $success = $order->transitionTo($validated['status']);

        if (!$success) {
            return back()->with('error', 'Statut invalide.');
        }

        // ✅ Invalider le cache dashboard après changement de statut
        Cache::forget('admin.dashboard.stats');

        return back()->with('success', 'Statut mis à jour.');
    }
}
