<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceNotification;
use App\Models\Order;
use App\Models\Product;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return match ($request->user()->role) {
            'seller' => $this->seller($request),
            'admin' => $this->admin($request),
            default => $this->buyer($request),
        };
    }

    public function buyer(Request $request): View
    {
        $user = $request->user();

        return view('dashboard.buyer', [
            'orders' => Order::query()->where('buyer_id', $user->id)->latest()->take(5)->get(),
            'wallet' => Wallet::firstOrCreate(['user_id' => $user->id]),
            'notifications' => MarketplaceNotification::query()->where('user_id', $user->id)->latest()->take(5)->get(),
        ]);
    }

    public function seller(Request $request): View
    {
        $user = $request->user();

        return view('dashboard.seller', [
            'products' => Product::query()->where('seller_id', $user->id)->latest()->take(6)->get(),
            'orders' => Order::query()->where('seller_id', $user->id)->latest()->take(6)->get(),
            'wallet' => Wallet::firstOrCreate(['user_id' => $user->id]),
        ]);
    }

    public function admin(Request $request): View
    {
        return view('dashboard.admin', [
            'buyers' => Order::query()->distinct('buyer_id')->count('buyer_id'),
            'products' => Product::query()->count(),
            'orders' => Order::query()->count(),
            'pendingOrders' => Order::query()->where('status', Order::STATUS_PENDING_PAYMENT)->count(),
        ]);
    }
}