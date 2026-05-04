<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller {
    public function index() {
        $categories = Category::active()->parent()->ordered()->with('children')->take(10)->get();

        $popularProducts = Product::active()->inStock()->popular()
            ->with(['category', 'seller'])->take(12)->get();

        $topupProducts = Product::active()->inStock()->ofType('topup')
            ->with(['category', 'seller'])->take(8)->get();

        $gameKeyProducts = Product::active()->inStock()->ofType('gamekey')
            ->with(['category', 'seller'])->take(8)->get();

        return view('home', compact(
            'categories', 'popularProducts', 'topupProducts', 'gameKeyProducts'
        ));
    }
}