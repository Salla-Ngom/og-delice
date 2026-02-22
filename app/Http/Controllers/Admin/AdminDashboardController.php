<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();

        $totalRevenue = Order::where('status', '!=', 'annulee')
            ->sum('total_price');

        // Commandes récentes
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Ventes des 7 derniers jours
        $salesData = Order::where('status', '!=', 'annulee')
            ->whereDate('created_at', '>=', Carbon::now()->subDays(7))
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('d M');
            });

        $chartLabels = [];
        $chartValues = [];

        foreach ($salesData as $day => $orders) {
            $chartLabels[] = $day;
            $chartValues[] = $orders->sum('total_price');
        }

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalProducts',
            'totalOrders',
            'totalRevenue',
            'recentOrders',
            'chartLabels',
            'chartValues'
        ));
    }
}