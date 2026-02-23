<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')
            ->latest()
            ->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('user', 'items.product');

        return view('admin.orders.show', compact('order'));
    }

public function updateStatus(Request $request, Order $order)
{
    $validated = $request->validate([
        'status' => 'required|in:en_attente,en_preparation,prete,annulee'
    ]);

    $order->update([
        'status' => $validated['status']
    ]);

    return back()->with('success', 'Statut mis à jour avec succès');
}
}
