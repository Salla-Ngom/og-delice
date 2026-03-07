<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class AdminLiveController extends Controller
{
    /**
     * Endpoint de polling — appelé toutes les 30s par le JS du layout admin.
     * Retourne uniquement les données nécessaires pour mettre à jour l'UI sans rechargement.
     * Cache 20s — évite de marteau la DB si plusieurs admins connectés.
     */
    public function poll(): JsonResponse
    {
        $userId = auth()->id();

        $data = Cache::remember("admin.poll.{$userId}", 20, function () {
            $pendingOrders = Order::where('status', 'en_attente')->count();
            $unreadNotifs  = auth()->user()->unreadNotifications->count();

            // 5 dernières commandes pour mise à jour du dashboard sans rechargement
            $recentOrders = Order::with('user')
                ->latest()
                ->take(5)
                ->get()
                ->map(fn($o) => [
                    'id'          => $o->id,
                    'user_name'   => $o->user?->name ?? 'Client supprimé',
                    'total'       => $o->formatted_total,
                    'status'      => $o->status,
                    'status_label'=> $o->status_label,
                    'status_badge'=> $o->status_badge,
                    'time'        => $o->created_at?->diffForHumans(),
                    'url'         => route('admin.orders.show', $o->id),
                ]);

            return [
                'pending_orders'  => $pendingOrders,
                'unread_notifs'   => $unreadNotifs,
                'recent_orders'   => $recentOrders,
                'timestamp'       => now()->toIso8601String(),
            ];
        });

        return response()->json($data);
    }
}
