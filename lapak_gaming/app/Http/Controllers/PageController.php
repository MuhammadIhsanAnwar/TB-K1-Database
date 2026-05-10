<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function contact() {
        return view('pages.contact');
    }

    public function terms() {
        return view('pages.terms');
    }
}