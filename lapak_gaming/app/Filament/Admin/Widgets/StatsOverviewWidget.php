<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\WalletTransaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalUsers = User::count();
        $totalSellers = User::where('role', 'seller')->count();
        $totalProducts = Product::where('is_active', true)->count();
        $totalOrders = Order::count();
        $totalRevenue = WalletTransaction::where('type', 'income')->sum('amount');

        return [
            Stat::make('Total Users', $totalUsers)
                ->icon('heroicon-o-users')
                ->color('primary'),

            Stat::make('Active Sellers', $totalSellers)
                ->icon('heroicon-o-briefcase')
                ->color('success'),

            Stat::make('Total Products', $totalProducts)
                ->icon('heroicon-o-shopping-cart')
                ->color('warning'),

            Stat::make('Total Orders', $totalOrders)
                ->icon('heroicon-o-inbox')
                ->color('info'),

            Stat::make('Total Revenue', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->icon('heroicon-o-banknotes')
                ->color('danger'),
        ];
    }
}
