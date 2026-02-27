<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')   // ✅ eager loading
            ->latest()
            ->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::active()->orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id'    => ['required', 'exists:categories,id'],
            'name'           => ['required', 'string', 'max:150'],
            'description'    => ['nullable', 'string', 'max:2000'],
            'price'          => ['required', 'numeric', 'min:0', 'max:9999999'],
            'discount_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'stock'          => ['required', 'integer', 'min:0', 'max:100000'],
            'service_type'   => ['nullable', Rule::in(array_keys(Product::SERVICE_TYPES))],
            'preparation_time' => ['nullable', 'integer', 'min:1', 'max:480'],
            'sort_order'     => ['nullable', 'integer', 'min:0'],
            'image'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active'      => ['sometimes', 'boolean'],
            'is_featured'    => ['sometimes', 'boolean'],
            'is_popular'     => ['sometimes', 'boolean'],
            'is_traiteur'    => ['sometimes', 'boolean'],
        ]);

        // Checkboxes HTML non cochées = absentes de la requête → forcer false
        $validated['is_active']   = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_popular']  = $request->boolean('is_popular');
        $validated['is_traiteur'] = $request->boolean('is_traiteur');

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('products', 'public');
        }

        // slug auto-généré par boot() — ne pas le passer dans create()
        Product::create($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produit ajouté avec succès.');
    }

    public function edit(Product $product)
    {
        $categories = Category::active()->orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'category_id'    => ['required', 'exists:categories,id'],
            'name'           => ['required', 'string', 'max:150'],
            'description'    => ['nullable', 'string', 'max:2000'],
            'price'          => ['required', 'numeric', 'min:0', 'max:9999999'],
            'discount_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'stock'          => ['required', 'integer', 'min:0', 'max:100000'],
            'service_type'   => ['nullable', Rule::in(array_keys(Product::SERVICE_TYPES))],
            'preparation_time' => ['nullable', 'integer', 'min:1', 'max:480'],
            'sort_order'     => ['nullable', 'integer', 'min:0'],
            'image'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active'      => ['sometimes', 'boolean'],
            'is_featured'    => ['sometimes', 'boolean'],
            'is_popular'     => ['sometimes', 'boolean'],
            'is_traiteur'    => ['sometimes', 'boolean'],
        ]);

        $validated['is_active']   = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_popular']  = $request->boolean('is_popular');
        $validated['is_traiteur'] = $request->boolean('is_traiteur');

        if ($request->hasFile('image')) {
            // ✅ Supprimer l'ancienne image avant d'en stocker une nouvelle
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')
                ->store('products', 'public');
        }

        $product->update($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produit modifié avec succès.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        // ✅ Vérifier qu'il n'y a pas de commandes liées (restrictOnDelete en DB)
        if ($product->orderItems()->exists()) {
            return back()->with(
                'error',
                'Ce produit ne peut pas être supprimé car il est lié à des commandes.'
            );
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return back()->with('success', 'Produit supprimé.');
    }
}
