<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'users');

        // 1. Ambil Data (Pastikan namanya $regularUsers agar cocok dengan Blade)
        $regularUsers = User::where('role', 'buyer')
            ->orderByDesc('created_at')
            ->paginate(15, ['*'], 'users_page')->appends(['tab' => 'users']);

        $sellers = User::where('role', 'seller')
            ->orderByDesc('created_at')
            ->paginate(15, ['*'], 'sellers_page')->appends(['tab' => 'sellers']);

        $applications = User::where('seller_status', 'pending')
            ->orderByDesc('created_at')
            ->paginate(15, ['*'], 'apps_page')->appends(['tab' => 'applications']);

        // 2. Hitung Statistik
        $counts = [
            'users' => User::where('role', 'buyer')->count(),
            'sellers' => User::where('role', 'seller')->count(),
            'apps' => User::where('seller_status', 'pending')->count(),
        ];

        // 3. Kirim variabel $regularUsers (BUKAN $users)
        return view('admin.users.index', compact('regularUsers', 'sellers', 'applications', 'counts', 'tab'));
    }

    public function updateUserStatus(Request $request, User $user)
    {
        $user->update([
            'status' => $request->status,
            'suspend_reason' => $request->suspend_reason
        ]);

        return back()->with('success', "Status user {$user->name} berhasil diperbarui.");
    }

    public function approveSeller(User $user)
    {
        $user->update([
            'role' => 'seller',
            'seller_status' => 'approved',
            'is_seller' => true
        ]);

        return back()->with('success', "{$user->name} sekarang resmi menjadi Seller.");
    }

    public function rejectSeller(User $user)
    {
        $user->update([
            'seller_status' => 'rejected'
        ]);

        return back()->with('success', "Pengajuan seller {$user->name} telah ditolak.");
    }

    public function destroyUser(User $user)
    {
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ORDERS
    // ─────────────────────────────────────────────────────────────────────────

    public function orders(Request $request): View
    {
        $orders = Order::query()->with(['buyer', 'seller'])->latest()->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function showOrder(Order $order): View
    {
        $order->load(['buyer', 'seller', 'items.product']);

        return view('admin.orders.show', compact('order'));
    }
}