<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Product; // Pastikan model ini ada
// use App\Models\Banner; // Buka comment ini jika kamu punya model Banner
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    // ─── 1. KELOLA AKUN & PENGAJUAN ──────────────────────────────────────────

    public function index(Request $request)
    {
        $tab = $request->get('tab', 'users');

        $regularUsers = User::where('role', 'buyer')
            ->orderByDesc('created_at')
            ->paginate(15, ['*'], 'users_page')->appends(['tab' => 'users']);

        $sellers = User::where('role', 'seller')
            ->orderByDesc('created_at')
            ->paginate(15, ['*'], 'sellers_page')->appends(['tab' => 'sellers']);

        $applications = User::where('seller_status', 'pending')
            ->orderByDesc('created_at')
            ->paginate(15, ['*'], 'apps_page')->appends(['tab' => 'applications']);

        $counts = [
            'users' => User::where('role', 'buyer')->count(),
            'sellers' => User::where('role', 'seller')->count(),
            'apps' => User::where('seller_status', 'pending')->count(),
        ];

        return view('admin.users.index', compact('regularUsers', 'sellers', 'applications', 'counts', 'tab'));
    }

    public function updateUserStatus(Request $request, User $user)
    {
        $user->update([
            'status' => $request->status,
            'suspend_reason' => $request->suspend_reason
        ]);
        return back()->with('success', "Status akun {$user->name} berhasil diperbarui.");
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

    public function rejectSeller(Request $request, User $user)
    {
        $user->update([
            'seller_status' => 'rejected'
            // Jika ada kolom rejection_reason, tambahkan di sini
        ]);
        return back()->with('success', "Pengajuan seller {$user->name} telah ditolak.");
    }

    public function destroyUser(User $user)
    {
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }

    // ─── 2. KELOLA BANNERS (Fungsi yang tadi hilang) ─────────────────────────

    public function banners()
    {
        // Jika kamu belum punya tabel banners, buat dummy saja dulu agar tidak error
        $banners = []; // Ganti dengan Banner::all() jika sudah ada modelnya
        return view('admin.banners.index', compact('banners'));
    }

    public function storeBanner(Request $request)
    {
        // Logika simpan banner kamu di sini
        return back()->with('success', 'Banner berhasil ditambahkan.');
    }

    public function destroyBanner($id)
    {
        // Logika hapus banner kamu di sini
        return back()->with('success', 'Banner berhasil dihapus.');
    }

    // ─── 3. KELOLA ORDERS (Fungsi yang tadi hilang) ──────────────────────────

    public function orders()
    {
        $orders = Order::latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    // ─── 4. NOTIFICATIONS ────────────────────────────────────────────────────

    public function notifications()
    {
        // Kita ambil data notifikasi dari database. 
        // Kalau kamu pakai sistem notifikasi bawaan Laravel, biasanya tabelnya namanya 'notifications'.
        // Kita pakai DB facade aja biar aman kalau kamu belum buat Model-nya.

        $notifications = \Illuminate\Support\Facades\DB::table('notifications')
            ->latest()
            ->paginate(10);

        return view('admin.notifications.index', compact('notifications'));
    }
}