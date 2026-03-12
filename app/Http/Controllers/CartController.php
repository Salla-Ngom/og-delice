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

        $productIds = array_keys($cart);
        $products   = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $total = 0;
        foreach ($cart as $id => $item) {
            $product = $products->get($id);
            if ($product) {
                $total += $product->final_price * $item['quantity'];
                // ✅ Resynchronise prix ET image à chaque affichage du panier
                // Couvre les sessions anciennes qui avaient le chemin brut
                $cart[$id]['price'] = $product->final_price;
                $cart[$id]['image'] = $product->image_url; // ← sync ici aussi
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

        if ($currentQty >= $product->stock) {
            return response()->json([
                'success' => false,
                'message' => 'Stock insuffisant.',
            ], 422);
        }

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'name'     => $product->name,
                'price'    => $product->final_price,
                // ✅ image_url = URL complète via accessor Product
                // $product->image = 'products/xxx.jpg' (chemin relatif storage)
                // $product->image_url = asset('storage/products/xxx.jpg') ← ce qu'il faut dans src=""
                'image'    => $product->image_url,
                'quantity' => 1,
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success'   => true,
            'cartCount' => collect($cart)->sum('quantity'),
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