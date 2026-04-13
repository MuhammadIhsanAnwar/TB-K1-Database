<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(Product $product): View
    {
        $product->load(['seller.sellerLevel', 'category', 'reviews.user']);

        return view('marketplace.product', [
            'product' => $product,
            'relatedProducts' => Product::query()->published()->where('category_id', $product->category_id)->whereKeyNot($product->getKey())->latest()->take(6)->get(),
        ]);
    }
}