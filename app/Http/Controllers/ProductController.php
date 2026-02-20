<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function menu()
    {
        $categories = Category::with(['products' => function ($query)
        {
            $query->where('is_active', true);
        }])->get();

            return view('client.menu', compact('categories'));
    }
}
