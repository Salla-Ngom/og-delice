<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        // Récupérer les statistiques
         $categories = Category::with(['products' => function ($query) {
        $query->where('is_active', true);
    }])->get();

    return view('welcome', compact('categories'));}
}
