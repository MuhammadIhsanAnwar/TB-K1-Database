<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceNotification;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $pendingEmailVerifications = Schema::hasTable('users') ? User::query()->whereNull('email_verified_at')->count() : 0;

        $chartLabels = collect();
        $chartTransactions = collect();
        $chartRevenue = collect();
        $chartRangeLabel = 'Belum ada transaksi';

        if (Schema::hasTable('orders')) {
            $firstOrderDate = Order::query()->min('created_at');
            $startMonth = $firstOrderDate ? Carbon::parse($firstOrderDate)->startOfMonth() : now()->startOfMonth();
            $endMonth = now()->startOfMonth();
            $chartRangeLabel = $startMonth->format('M Y') . ' - ' . $endMonth->format('M Y');

            for ($cursor = $startMonth->copy(); $cursor <= $endMonth; $cursor->addMonth()) {
                $monthStart = $cursor->copy()->startOfMonth();
                $monthEnd = $cursor->copy()->endOfMonth();
                $chartLabels->push($monthStart->format('M Y'));
                $chartTransactions->push(Order::whereBetween('created_at', [$monthStart, $monthEnd])->count());

                if (Schema::hasTable('order_financials')) {
                    $chartRevenue->push(
                        (float) Order::query()
                            ->leftJoin('order_financials', 'orders.id', '=', 'order_financials.order_id')
                            ->whereBetween('orders.created_at', [$monthStart, $monthEnd])
                            ->sum(DB::raw('COALESCE(order_financials.grand_total, orders.grand_total, 0)'))
                    );
                } else {
                    $chartRevenue->push(
                        (float) Order::query()
                            ->whereBetween('created_at', [$monthStart, $monthEnd])
                            ->sum('grand_total')
                    );
                }
            }
        }

        return view('dashboard.admin', [
            'totalUsers' => $totalUsers,
            'buyers' => $buyers,
            'sellers' => $sellers,
            'suspendedUsers' => $suspendedUsers,
            'sellerRequests' => $sellerRequests,
            'pendingEmailVerifications' => $pendingEmailVerifications,
            'products' => Schema::hasTable('products') ? Product::query()->count() : 0,
            'orders' => Schema::hasTable('orders') ? Order::query()->count() : 0,
            'chartLabels' => $chartLabels,
            'chartTransactions' => $chartTransactions,
            'chartRevenue' => $chartRevenue,
            'chartRangeLabel' => $chartRangeLabel,
        ]);
    }
}
