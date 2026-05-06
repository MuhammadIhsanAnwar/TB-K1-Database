<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        return view('wishlist.index');
    }

    public function add($productId)
    {
        // Wishlist functionality to be implemented
        return redirect()->back();
    }

    public function remove($productId)
    {
        // Wishlist functionality to be implemented
        return redirect()->back();
    }
}
