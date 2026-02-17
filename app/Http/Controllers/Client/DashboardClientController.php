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

          $categories = Category::with(['products' => function ($query) {
        $query->where('is_active', true);
    }])->get();

    $orders = $user->orders()
                   ->with('items')
                   ->latest()
                   ->get();

    return view('client.clientPage', compact('user', 'orders', 'categories'));
}

}
