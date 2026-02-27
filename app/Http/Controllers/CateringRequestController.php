<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        // Total calculé côté serveur depuis les prix DB — pas depuis la session
        $productIds = array_keys($cart);
        $products   = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $total = 0;
        foreach ($cart as $id => $item) {
            $product = $products->get($id);
            if ($product) {
                $total += $product->final_price * $item['quantity'];
                // Mettre à jour le prix en session si le produit a changé de prix
                $cart[$id]['price'] = $product->final_price;
            }
        }

        return view('cart.index', compact('cart', 'total', 'products'));
    }

    public function add(Request $request, int $id): JsonResponse
    {
        $product = Product::where('id', $id)
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->firstOrFail();

        $cart = session()->get('cart', []);

        $currentQty = $cart[$id]['quantity'] ?? 0;

        // Vérifier le stock disponible
        if ($currentQty >= $product->stock) {
            return response()->json([
                'success' => false,
                'message' => 'Stock insuffisant.',
            ], 422);
        }

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            // On stocke uniquement l'ID en session — le prix est relu depuis la DB
            $cart[$id] = [
                'name'     => $product->name,
                'price'    => $product->final_price, // prix courant au moment de l'ajout
                'image'    => $product->image,
                'quantity' => 1,
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success'   => true,
            'cartCount' => collect($cart)->sum('quantity'), // total des articles, pas des lignes
            'message'   => $product->name . ' ajouté au panier.',
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = session()->get('cart', []);

        if (!isset($cart[$id])) {
            return redirect()->route('cart.index')
                ->with('error', 'Produit introuvable dans le panier.');
        }

        // Vérifier le stock disponible
        $product = Product::find($id);
        if ($product && $request->quantity > $product->stock) {
            return redirect()->route('cart.index')
                ->with('error', 'Quantité demandée indisponible en stock.');
        }

        $cart[$id]['quantity'] = $request->quantity;
        session()->put('cart', $cart);

        return redirect()->route('cart.index')
            ->with('success', 'Panier mis à jour.');
    }

    public function remove(int $id): RedirectResponse
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')
            ->with('success', 'Produit retiré du panier.');
    }
}
