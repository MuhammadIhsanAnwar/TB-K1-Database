<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\MarketplaceNotification;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    // 1. KELOLA AKUN (Unified Tabbed Page)
    // ─────────────────────────────────────────────────────────────────────────

    public function index(Request $request): View
    {
        $tab = $request->query('tab', 'users');

        // Tab Buyer
        $regularUsers = User::query()
            ->where('role', 'buyer')
            ->orderByDesc('created_at')
            ->paginate(15, ['*'], 'users_page')
            ->appends(['tab' => 'users']);

        // Tab Seller
        $sellers = User::query()
            ->where('role', 'seller')
            ->orderByDesc('created_at')
            ->paginate(15, ['*'], 'sellers_page')
            ->appends(['tab' => 'sellers']);

        // Tab Pengajuan
        $applications = User::query()
            ->where('seller_status', 'pending')
            ->orderByDesc('created_at')
            ->paginate(15, ['*'], 'apps_page')
            ->appends(['tab' => 'applications']);

        // Statistik Badge (Real Count)
        $counts = [
            'users' => User::where('role', 'buyer')->count(),
            'sellers' => User::where('role', 'seller')->count(),
            'apps' => User::where('seller_status', 'pending')->count(),
        ];

        return view('admin.users.index', compact('tab', 'regularUsers', 'sellers', 'applications', 'counts'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 2. USER ACTIONS (Status & Delete)
    // ─────────────────────────────────────────────────────────────────────────

    public function updateUserStatus(Request $request, User $user): RedirectResponse
    {
        if ($user->role === 'admin' && $user->id !== Auth::id()) {
            return back()->withErrors(['status' => 'Tidak dapat mengubah status akun admin lain.']);
        }

        $data = $request->validate([
            'status' => ['required', 'in:active,suspended'],
            'suspend_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $updates = [
            'status' => $data['status'],
            'suspended_at' => $data['status'] === 'suspended' ? now() : null,
            'suspend_reason' => $data['status'] === 'suspended' ? ($data['suspend_reason'] ?? null) : null,
        ];

        $user->forceFill($updates)->save();

        if ($data['status'] === 'suspended') {
            \DB::table('sessions')->where('user_id', $user->id)->delete();
        }

        return back()->with('success', "Akun {$user->name} berhasil diperbarui.");
    }

    public function destroyUser(User $user): RedirectResponse
    {
        if ($user->role === 'admin') {
            return back()->withErrors(['delete' => 'Tidak dapat menghapus akun admin.']);
        }
        $user->delete();
        return back()->with('success', 'Pengguna telah dihapus.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 3. SELLER WORKFLOW (Approve / Reject)
    // ─────────────────────────────────────────────────────────────────────────

    public function approveSeller(User $user): RedirectResponse
    {
        if ($user->seller_status !== 'pending') {
            return back()->withErrors(['approve' => 'Pengajuan ini tidak dalam status pending.']);
        }

        $user->forceFill([
            'role' => 'seller',
            'seller_status' => 'approved',
            'seller_rejection_reason' => null,
            'status' => 'active',
            'is_seller' => true,
        ])->save();

        MarketplaceNotification::create([
            'user_id' => $user->id,
            'title' => 'Pengajuan Seller Disetujui',
            'body' => "Selamat! Toko \"{$user->shop_name}\" Anda telah diverifikasi. Anda sekarang bisa berjualan.",
            'link' => route('seller.dashboard'),
            'type' => 'seller-approved',
        ]);

        return back()->with('success', "Pengajuan seller {$user->name} berhasil disetujui.");
    }

    public function rejectSeller(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'rejection_reason' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        $user->forceFill([
            'seller_status' => 'rejected',
            'seller_rejection_reason' => $data['rejection_reason'],
        ])->save();

        MarketplaceNotification::create([
            'user_id' => $user->id,
            'title' => 'Pengajuan Seller Ditolak',
            'body' => "Maaf, pengajuan toko Anda ditolak. Alasan: {$data['rejection_reason']}.",
            'link' => route('seller.register.form'),
            'type' => 'seller-rejected',
        ]);

        return back()->with('success', "Pengajuan seller {$user->name} ditolak.");
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 4. BANNERS, NOTIFICATIONS & ORDERS (Back to Life!)
    // ─────────────────────────────────────────────────────────────────────────

    public function banners(): View
    {
        $banners = Banner::query()->latest()->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function storeBanner(Request $request): RedirectResponse
    {
        // ... (Gunakan logika storeBanner asli kamu di sini) ...
        return back()->with('success', 'Banner berhasil disimpan.');
    }

    public function destroyBanner(Banner $banner): RedirectResponse
    {
        $banner->delete();
        return back()->with('success', 'Banner berhasil dihapus.');
    }

    public function notifications(): View
    {
        // PAKAI MODEL ASLI KAMU: MarketplaceNotification
        $notifications = MarketplaceNotification::query()->latest()->paginate(20);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function sendNotification(Request $request): RedirectResponse
    {
        // ... (Gunakan logika broadcast asli kamu di sini) ...
        return back()->with('success', 'Notifikasi berhasil dikirim.');
    }

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