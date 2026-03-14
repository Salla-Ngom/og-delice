<?php

namespace App\Http\Controllers\Vendeur;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class VendeurOrderController extends Controller
{
    /**
     * Interface POS — caisse vendeur
     */
    public function pos()
    {
        $products = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->with('category')
            ->orderBy('name')
            ->get();

        $categories = \App\Models\Category::whereHas('products', function ($q) {
            $q->where('is_active', true)->where('stock', '>', 0);
        })->orderBy('name')->get();

        return view('vendeur.pos', compact('products', 'categories'));
    }

    /**
     * Enregistrer une vente depuis la caisse
     */
    public function store(Request $request)
    {
        $request->validate([
            'items'            => ['required', 'array', 'min:1'],
            'items.*.id'       => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'customer_name'    => ['nullable', 'string', 'max:255'],
            'note'             => ['nullable', 'string', 'max:500'],
        ]);

        $order = DB::transaction(function () use ($request) {

            // Relire les prix depuis la DB — jamais depuis le client
            $productIds = array_column($request->items, 'id');
            $products   = Product::whereIn('id', $productIds)
                ->where('is_active', true)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $total = 0;
            $lines = [];

            foreach ($request->items as $item) {
                $product = $products->get($item['id']);

                if (!$product) {
                    throw new \Exception("Produit #{$item['id']} introuvable ou inactif.");
                }

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stock insuffisant pour « {$product->name} » (stock : {$product->stock}).");
                }

                $unitPrice      = $product->final_price;
                $unitPricePromo = $product->discount_price;
                $subtotal       = $unitPrice * $item['quantity'];
                $total         += $subtotal;

                $lines[] = [
                    'product'         => $product,
                    'quantity'        => $item['quantity'],
                    'unit_price'      => $unitPrice,
                    'unit_price_promo'=> $unitPricePromo,
                ];
            }

            // Créer la commande — vendeur_id = auth()->id()
            $order                = new Order();
            $order->user_id       = auth()->id(); // ← le vendeur est "propriétaire" de la vente
            $order->vendeur_id    = auth()->id(); // ← colonne pour distinguer vente POS
            $order->total_price   = $total;
            $order->status        = 'livree';    // ← vente directe = immédiatement livrée
            $order->customer_name = $request->customer_name; // nom client walk-in (nullable)
            $order->note          = $request->note;
            $order->source        = 'pos';       // distingue vente POS vs commande en ligne
            $order->save();

            // Créer les lignes + décrémenter stock
            foreach ($lines as $line) {
                OrderItem::create([
                    'order_id'         => $order->id,
                    'product_id'       => $line['product']->id,
                    'quantity'         => $line['quantity'],
                    'unit_price'       => $line['unit_price'],
                    'unit_price_promo' => $line['unit_price_promo'],
                ]);

                $line['product']->decrement('stock', $line['quantity']);
            }

            return $order;
        });

        return response()->json([
            'success'    => true,
            'order_id'   => $order->id,
            'reference'  => $order->reference, // accessor
            'receipt_url'=> route('vendeur.orders.receipt', $order),
        ]);
    }

    /**
     * Historique des ventes du vendeur
     */
    public function index()
    {
        $orders = Order::where('vendeur_id', auth()->id())
            ->with(['items.product'])
            ->latest()
            ->paginate(20);

        $todayTotal = Order::where('vendeur_id', auth()->id())
            ->whereDate('created_at', today())
            ->where('status', 'livree')
            ->sum('total_price');

        $todayCount = Order::where('vendeur_id', auth()->id())
            ->whereDate('created_at', today())
            ->count();

        return view('vendeur.orders.index', compact('orders', 'todayTotal', 'todayCount'));
    }

    /**
     * Générer le reçu PDF d'une commande
     */
    public function receipt(Order $order)
    {
        // Seul le vendeur propriétaire ou un admin peut voir le reçu
        if (auth()->user()->role === 'vendeur' && $order->vendeur_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.product');

        $pdf = Pdf::loadView('vendeur.receipt', compact('order'))
            ->setPaper([0, 0, 226, 600], 'portrait') // format ticket 80mm
            ->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
            ]);

        $filename = 'recu-' . $order->reference . '.pdf';

        return $pdf->stream($filename); // stream = afficher dans le navigateur (imprimable)
    }
}
