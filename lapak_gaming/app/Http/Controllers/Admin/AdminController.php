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
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    // KELOLA AKUN — tabbed page (Users / Sellers / Pengajuan Seller)
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Halaman utama kelola akun dengan tiga tab.
     * Tab ditentukan oleh query string ?tab=users|sellers|applications
     */
    public function accounts(Request $request): View
    {
        // 1. Ambil parameter tab (default: buyers)
        $tab = $request->query('tab', 'buyers');

        // 2. Ambil data Buyers (Sesuai query di log error kamu)
        $buyers = User::where('role', 'buyer')
            ->where(function ($q) {
                $q->where('seller_status', 'none')
                    ->orWhereNull('seller_status');
            })
            ->where('role', '!=', 'admin')
            ->orderByDesc('created_at')
            ->paginate(20);

        // 3. Ambil data Sellers yang sudah approved
        $sellers = User::where(function ($q) {
            $q->where('role', 'seller')
                ->orWhere('is_seller', 1);
        })
            ->where('seller_status', 'approved')
            ->paginate(20);

        // 4. Hitung jumlah yang sedang pending (untuk statistik di header)
        $pendingCount = User::where('seller_status', 'pending')->count();

        // 5. KIRIM SEMUANYA KE VIEW (Jangan sampai ada yang ketinggalan di compact)
        return view('admin.accounts.index', compact('buyers', 'sellers', 'pendingCount', 'tab'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // USERS
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Daftar semua user (legacy – masih digunakan oleh beberapa route lama).
     */
    public function users(Request $request): View
    {
        $tab = $request->query('tab', 'users');

        $regularUsers = User::query()
            ->where('role', '!=', 'admin')
            ->orderByDesc('created_at')
            ->paginate(20);

        $sellers = User::query()
            ->where('role', 'seller')
            ->orderByDesc('created_at')
            ->paginate(20);

        $applications = User::query()
            ->where('role', 'buyer')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.users.index', compact('regularUsers', 'sellers', 'applications', 'tab'));
    }

    public function showUser(User $user): View
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Admin HANYA boleh mengubah status akun (active / suspended) beserta alasan.
     * Tidak boleh mengubah nama, email, password, atau data pribadi lain.
     */
    public function updateUserStatus(Request $request, User $user): RedirectResponse
    {
        // Prevent admin from modifying another admin account
        if ($user->role === 'admin' && $user->id !== Auth::id()) {
            return back()->withErrors(['status' => 'Tidak dapat mengubah status akun admin lain.']);
        }

        $data = $request->validate([
            'status' => ['required', 'in:active,suspended'],
            'suspend_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $updates = ['status' => $data['status']];

        if ($data['status'] === 'suspended') {
            $updates['suspended_at'] = now();
            $updates['suspend_reason'] = $data['suspend_reason'] ?? null;
        } else {
            // Reactivating — clear suspend data
            $updates['suspended_at'] = null;
            $updates['suspend_reason'] = null;
        }

        $user->forceFill($updates)->save();

        // Invalidate all sessions for the suspended user so they are logged out immediately
        if ($data['status'] === 'suspended') {
            // Laravel session invalidation by user ID (works with database driver)
            // For file-based sessions this is best-effort via status check at login
            \DB::table('sessions')
                ->where('user_id', $user->id)
                ->delete();
        }

        $label = $data['status'] === 'suspended' ? 'disuspend' : 'diaktifkan';

        return back()->with('success', "Akun {$user->name} berhasil {$label}.");
    }

    public function destroyUser(User $user): RedirectResponse
    {
        if ($user->role === 'admin') {
            return back()->withErrors(['delete' => 'Tidak dapat menghapus akun admin.']);
        }

        $user->delete();

        return redirect()->route('admin.accounts')->with('success', 'Pengguna telah dihapus.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // SELLER APPLICATION WORKFLOW
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Approve pengajuan seller.
     * Sets: role = seller, is_seller = true, seller_status = approved.
     */
    public function approveSeller(User $user): RedirectResponse
    {
        if ($user->seller_status !== 'pending') {
            return back()->withErrors(['approve' => 'Pengajuan ini tidak dalam status pending.']);
        }

        $updates = [
            'role' => 'seller',
            'seller_status' => 'approved',
            'seller_rejection_reason' => null,
            'status' => 'active',
        ];

        if (Schema::hasColumn('users', 'is_seller')) {
            $updates['is_seller'] = true;
        }

        $user->forceFill($updates)->save();

        // Notify the user
        if (Schema::hasTable('marketplace_notifications')) {
            MarketplaceNotification::create([
                'user_id' => $user->id,
                'title' => 'Pengajuan Seller Disetujui',
                'body' => "Selamat! Toko \"{$user->shop_name}\" Anda telah diverifikasi oleh admin. Anda sekarang dapat mulai berjualan di Lapak Gaming.",
                'link' => route('seller.dashboard'),
                'type' => 'seller-approved',
            ]);
        }

        return back()->with('success', "Pengajuan seller {$user->name} ({$user->shop_name}) berhasil disetujui.");
    }

    /**
     * Reject pengajuan seller dengan alasan wajib.
     */
    public function rejectSeller(Request $request, User $user): RedirectResponse
    {
        if ($user->seller_status !== 'pending') {
            return back()->withErrors(['reject' => 'Pengajuan ini tidak dalam status pending.']);
        }

        $data = $request->validate([
            'rejection_reason' => ['required', 'string', 'min:10', 'max:1000'],
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
            'rejection_reason.min' => 'Alasan penolakan minimal 10 karakter.',
            'rejection_reason.max' => 'Alasan penolakan maksimal 1000 karakter.',
        ]);

        $user->forceFill([
            'seller_status' => 'rejected',
            'seller_rejection_reason' => $data['rejection_reason'],
        ])->save();

        // Notify the user
        if (Schema::hasTable('marketplace_notifications')) {
            MarketplaceNotification::create([
                'user_id' => $user->id,
                'title' => 'Pengajuan Seller Ditolak',
                'body' => "Mohon maaf, pengajuan toko \"{$user->shop_name}\" Anda ditolak. Alasan: {$data['rejection_reason']}. Anda dapat mengajukan kembali setelah memperbaiki data toko.",
                'link' => route('seller.register.form'),
                'type' => 'seller-rejected',
            ]);
        }

        return back()->with('success', "Pengajuan seller {$user->name} berhasil ditolak.");
    }

    // ─────────────────────────────────────────────────────────────────────────
    // BANNERS
    // ─────────────────────────────────────────────────────────────────────────

    public function banners(): View
    {
        $banners = Schema::hasTable('banners') ? Banner::query()->latest()->get() : collect();

        return view('admin.banners.index', compact('banners'));
    }

    public function storeBanner(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'image_url' => ['nullable', 'url', 'max:2048'],
            'link_url' => ['nullable', 'url', 'max:2048'],
            'position' => ['required', 'in:hero,featured,sidebar'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $imagePath = null;
        $imageUrl = $data['image_url'] ?? null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'banner_' . time() . '.' . $file->getClientOriginalExtension();
            $imagePath = $file->storeAs('banners', $filename, 'public_app_public');
            $imageUrl = null;
        }

        if (!$imagePath && !$imageUrl) {
            return back()->withErrors(['image' => 'Unggah gambar atau sediakan URL gambar.']);
        }

        Banner::create([
            'title' => $data['title'],
            'subtitle' => $data['subtitle'] ?? null,
            'image_url' => $imageUrl,
            'image_path' => $imagePath,
            'link_url' => $data['link_url'] ?? null,
            'position' => $data['position'],
            'is_active' => (bool) ($data['is_active'] ?? true),
        ]);

        return back()->with('success', 'Banner berhasil disimpan.');
    }

    public function destroyBanner(Banner $banner): RedirectResponse
    {
        $banner->delete();

        return back()->with('success', 'Banner berhasil dihapus.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // NOTIFICATIONS
    // ─────────────────────────────────────────────────────────────────────────

    public function notifications(): View
    {
        $notifications = MarketplaceNotification::query()->latest()->limit(20)->get();

        return view('admin.notifications.index', compact('notifications'));
    }

    public function sendNotification(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'audience' => ['required', 'in:all,buyer,seller'],
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:2000'],
            'link' => ['nullable', 'url', 'max:2048'],
        ]);

        $users = User::query()
            ->when($data['audience'] !== 'all', fn($q) => $q->where('role', $data['audience']))
            ->get();

        foreach ($users as $user) {
            MarketplaceNotification::create([
                'user_id' => $user->id,
                'title' => $data['title'],
                'body' => $data['body'],
                'link' => $data['link'] ?? null,
                'type' => 'admin-broadcast',
            ]);
        }

        return back()->with('success', 'Notifikasi berhasil dikirim ke ' . $users->count() . ' akun.');
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