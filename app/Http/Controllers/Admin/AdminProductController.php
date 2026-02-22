<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->latest()
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'category_id' => 'required|exists:categories,id',
        'name' => 'required|string|max:150',
        'description' => 'nullable|string|max:2000',
        'price' => 'required|numeric|min:0|max:999999',
        'discount_price' => 'nullable|numeric|min:0|lt:price',
        'stock' => 'required|integer|min:0|max:100000',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'is_active' => 'sometimes|boolean',
        'is_featured' => 'sometimes|boolean',
    ]);

    if ($request->hasFile('image')) {
        $validated['image'] = $request->file('image')
            ->store('products','public');
    }

    $validated['is_active'] = $request->has('is_active');
    $validated['is_featured'] = $request->has('is_featured');

    Product::create($validated);

    return redirect()
        ->route('admin.products.index')
        ->with('success','Produit ajouté avec succès');
}

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();

        return view('admin.products.edit', compact('product','categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $data = $request->only([
            'category_id',
            'name',
            'description',
            'price'
        ]);

        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {

            // Supprimer ancienne image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $data['image'] = $request->file('image')
                ->store('products', 'public');
        }

        $product->update($data);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produit modifié avec succès');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return back()->with('success','Produit supprimé');
    }
}
