<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function menu(Request $request)
    {
        // Filtres depuis la requête
        $categoryId  = $request->integer('category') ?: null;
        $serviceType = $request->string('service_type')->toString() ?: null;
        $search      = $request->string('search')->toString() ?: null;

        $categories = Category::active()
            ->withAvailableProductsCount()
            ->get();

        $products = Product::available()
            ->byCategory($categoryId)
            ->byServiceType($serviceType)
            ->when($search, fn($q) =>
                $q->where('name', 'like', '%' . $search . '%')
            )
            ->with('category')
            ->ordered()
            ->paginate(12);

        return view('client.menu', compact('categories', 'products'));
    }
}
