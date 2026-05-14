<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceNotification;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    private function hasDashboardTables(): bool
    {
        return Schema::hasTable('orders')
            && Schema::hasTable('products')
            && Schema::hasTable('wallets')
            && Schema::hasTable('marketplace_notifications');
    }

    public function index(Request $request)
    {
        if (!$this->hasDashboardTables()) {
            return redirect()->route('setup.migrate');
        }

        return match ($request->user()->role) {
            'seller' => $this->seller($request),
            'admin' => $this->admin($request),
            default => $this->buyer($request),
        };
    }

    public function buyer(Request $request): View
    {
        $user = $request->user();
        $orders = Schema::hasTable('orders')
            ? Order::query()->where('buyer_id', $user->id)->latest()->take(5)->get()
            : collect();
        $notifications = Schema::hasTable('marketplace_notifications')
            ? MarketplaceNotification::query()->where('user_id', $user->id)->latest()->take(5)->get()
            : collect();
        $wallet = Schema::hasTable('wallets')
            ? Wallet::firstOrCreate(['user_id' => $user->id])
            : null;

        return view('dashboard.buyer', [
            'user' => $user,
            'orders' => $orders,
            'wallet' => $wallet,
            'notifications' => $notifications,
        ]);
    }

    public function seller(Request $request): View
    {
        $user = $request->user();
        $products = Schema::hasTable('products')
            ? Product::query()->where('seller_id', $user->id)->latest()->take(6)->get()
            : collect();
        $orders = Schema::hasTable('orders')
            ? Order::query()->where('seller_id', $user->id)->latest()->take(6)->get()
            : collect();
        $wallet = Schema::hasTable('wallets')
            ? Wallet::firstOrCreate(['user_id' => $user->id])
            : null;

        return view('dashboard.seller', [
            'user' => $user,
            'products' => $products,
            'orders' => $orders,
            'wallet' => $wallet,
        ]);
    }

    public function admin(Request $request): View
    {
        // ... (kode hitungan totalUsers, buyers, dll tetap sama) ...
        $totalUsers = Schema::hasTable('users') ? User::query()->count() : 0;
        $buyers = Schema::hasTable('users') ? User::query()->where('role', 'buyer')->count() : 0;
        $sellers = Schema::hasTable('users') ? User::query()->where('role', 'seller')->count() : 0;
        $suspendedUsers = Schema::hasTable('users') ? User::query()->where('status', 'suspended')->count() : 0;
        $sellerRequests = Schema::hasTable('users') ? User::query()->where('seller_status', 'pending')->count() : 0;

        $chartLabels = collect();
        $chartTransactions = collect();
        $chartRevenue = collect();

        for ($i = 5; $i >= 0; $i--) {
            $monthStart = now()->startOfMonth()->subMonths($i);
            $monthEnd = (clone $monthStart)->endOfMonth();
            $chartLabels->push($monthStart->format('M Y'));

            if (Schema::hasTable('orders')) {
                $chartTransactions->push(Order::whereBetween('created_at', [$monthStart, $monthEnd])->count());
            } else {
                $chartTransactions->push(0);
            }

            if (Schema::hasTable('wallet_transactions')) {
                $chartRevenue->push(
                    WalletTransaction::whereBetween('created_at', [$monthStart, $monthEnd])->sum('amount')
                );
            } else {
                $chartRevenue->push(0);
            }
        }

        return view('dashboard.admin', [
            'totalUsers' => $totalUsers,
            'buyers' => $buyers,
            'sellers' => $sellers,
            'suspendedUsers' => $suspendedUsers,
            'sellerRequests' => $sellerRequests,
            'products' => Schema::hasTable('products') ? Product::query()->count() : 0,
            'orders' => Schema::hasTable('orders') ? Order::query()->count() : 0,
            'chartLabels' => $chartLabels,
            'chartTransactions' => $chartTransactions,
            'chartRevenue' => $chartRevenue,
        ]);
    }
}