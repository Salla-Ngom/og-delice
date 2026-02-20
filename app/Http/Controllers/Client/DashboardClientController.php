<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;

class DashboardClientController extends Controller
{

public function index()
{
    $user = auth()->user();

    $orders = $user->orders()
                   ->with('items')
                   ->latest()
                   ->take(5)
                   ->get();

    $stats = [
        'total_orders'   => $user->orders()->count(),
        'pending_orders' => $user->orders()->where('status', 'pending')->count(),
        'total_spent'    => $user->orders()->where('status', 'completed')->sum('total_price'),
    ];

    return view('client.clientPage', compact('user','orders', 'stats'));
}
public function orders()
{
    $user = auth()->user();

    $orders = $user->orders()
                   ->with('items.product')
                   ->latest()
                   ->get();

    return view('client.orders', compact('orders'));
}

}
