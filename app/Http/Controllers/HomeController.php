<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // Suppression de l'import Menu — Menu.php est supprimé

        $categories = Category::active()
            ->withAvailableProductsCount()
            ->get();

        $featuredProducts = Product::available()
            ->featured()
            ->ordered()
            ->with('category')
            ->take(8)
            ->get();

        $popularProducts = Product::available()
            ->popular()
            ->ordered()
            ->with('category')
            ->take(6)
            ->get();

        return view('welcome', compact(
            'categories',
            'featuredProducts',
            'popularProducts',
        ));
    }
}
