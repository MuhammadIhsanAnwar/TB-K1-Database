<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\SellerAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var User $seller */
        $seller = Auth::user();
        $sellerAccount = $seller->sellerAccount;

        $stats = [
            'total_sales' => $sellerAccount->total_sales,
            'rating' => $sellerAccount->rating,
            'total_products' => $seller->products()->count(),
            'monthly_revenue' => $this->getMonthlyRevenue($seller),
            'pending_orders' => Order::where('seller_id', $seller->id)
                ->whereIn('status', ['payment_uploaded', 'processing'])
                ->count(),
        ];

        $recent_orders = Order::where('seller_id', $seller->id)
            ->with('buyer')
            ->latest()
            ->limit(10)
            ->get();

        $sales_chart = $this->getMonthlySalesData($seller);

        return view('seller.dashboard', compact('sellerAccount', 'stats', 'recent_orders', 'sales_chart'));
    }

    public function setup()
    {
        /** @var User $seller */
        $seller = Auth::user();

        if ($seller->sellerAccount) {
            return redirect()->route('seller.dashboard');
        }

        return view('seller.setup');
    }

    public function completeSetup(Request $request)
    {
        $validated = $request->validate([
            'shop_name' => 'required|string|max:255|unique:seller_accounts',
            'shop_description' => 'nullable|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'province' => 'required|string',
        ]);

        SellerAccount::create([
            'user_id' => Auth::id(),
            'shop_name' => $validated['shop_name'],
            'shop_description' => $validated['shop_description'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'province' => $validated['province'],
            'seller_level_id' => 1,
        ]);

        return redirect()->route('seller.dashboard')
            ->with('success', 'Toko berhasil dibuat!');
    }

    private function getMonthlyRevenue($seller)
    {
        return Order::where('seller_id', $seller->id)
            ->where('status', 'completed')
            ->whereMonth('completed_at', now()->month)
            ->sum('seller_income');
    }

    private function getMonthlySalesData($seller)
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $sales = Order::where('seller_id', $seller->id)
                ->where('status', 'completed')
                ->whereMonth('completed_at', $month->month)
               ->whereYear('completed_at', $month->year)
                ->sum('seller_income');
            
            $data[$month->format('M')] = $sales;
        }
        return $data;
    }
}
