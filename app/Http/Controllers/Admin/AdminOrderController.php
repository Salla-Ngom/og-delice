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
        $status = $request->string('status')->toString() ?: null;

        $orders = Order::with('user')
            ->where('source', 'online')   // ✅ exclut les ventes POS des vendeurs
            ->byStatus($status)
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', compact('orders', 'status'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);

        auth()->user()
            ->unreadNotifications()
            ->where('data->order_id', $order->id)
            ->update(['read_at' => now()]);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:' . implode(',', Order::STATUSES)],
        ]);

        $success = $order->transitionTo($validated['status']);

        if (!$success) {
            return back()->with('error', 'Statut invalide.');
        }

        Cache::forget('admin.dashboard.stats');

        return back()->with('success', 'Statut mis à jour.');
    }

    public function notifications()
    {
        $notifications = auth()->user()
            ->notifications()
            ->orderByRaw('read_at IS NOT NULL')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.notifications', compact('notifications'));
    }

    public function markAllRead(): RedirectResponse
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }
}
