<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // ✅ Cache 5 minutes — évite de recalculer à chaque requête
        $stats = Cache::remember('admin.dashboard.stats', 300, function () {
            return [
                'totalUsers'    => User::count(),
                'totalProducts' => Product::count(),
                'totalOrders'   => Order::count(),
                'totalRevenue'  => Order::where('status', '!=', 'annulee')->sum('total_price'),
                'pendingOrders' => Order::enAttente()->count(),
                'lowStock'      => Product::lowStock(5)->count(),
            ];
        });

        // Commandes récentes — pas mises en cache (doivent être temps réel)
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Ventes des 7 derniers jours — groupées en PHP après une seule requête
        $salesRaw = Order::where('status', '!=', 'annulee')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->orderBy('created_at')
            ->get(['created_at', 'total_price']);

        // Construire les 7 derniers jours avec zéro si aucune vente
        $chartLabels = [];
        $chartValues = [];

        for ($i = 6; $i >= 0; $i--) {
            $day   = now()->subDays($i)->format('d M');
            $total = $salesRaw
                ->filter(fn($o) => Carbon::parse($o->created_at)->format('d M') === $day)
                ->sum('total_price');

            $chartLabels[] = $day;
            $chartValues[] = round($total, 2);
        }

        return view('admin.dashboard', array_merge($stats, compact(
            'recentOrders',
            'chartLabels',
            'chartValues',
        )));
    }
}
