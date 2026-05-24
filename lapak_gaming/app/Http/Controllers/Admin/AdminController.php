<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\MarketplaceNotification;
use App\Models\NotificationBroadcast;
use App\Models\OrderFinancial;
use App\Models\Order;
use App\Models\User;
use App\Services\Pdf\PdfDocumentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminController extends Controller
{
    // ─── 1. KELOLA AKUN (Unified Tabbed Page) ────────────────────────────────

    public function index(Request $request): View
    {
        $tab = $request->query('tab', 'users');

        // Search and sorting params
        $q = trim((string) $request->query('q', ''));
        $sort = $request->query('sort', 'created_at');
        $direction = strtolower($request->query('direction', 'desc')) === 'asc' ? 'asc' : 'desc';

        $allowedSorts = ['created_at', 'name', 'email'];
        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'created_at';
        }

        $baseAppends = array_filter(['q' => $q, 'sort' => $sort, 'direction' => $direction]);

        $regularUsersQuery = User::query()
            ->where('role', 'buyer')
            ->when($q, fn($qb) => $qb->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%");
            }));

        $regularUsers = (clone $regularUsersQuery)
            ->orderBy($sort, $direction)
            ->paginate(15, ['*'], 'users_page')
            ->appends(array_merge($baseAppends, ['tab' => 'users']));

        $sellersQuery = User::query()
            ->where('role', 'seller')
            ->when($q, fn($qb) => $qb->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%");
            }));

        $sellers = (clone $sellersQuery)
            ->orderBy($sort, $direction)
            ->paginate(15, ['*'], 'sellers_page')
            ->appends(array_merge($baseAppends, ['tab' => 'sellers']));

        $applicationsQuery = User::query()
            ->where('seller_status', 'pending')
            ->when($q, fn($qb) => $qb->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%");
            }));

        $applications = (clone $applicationsQuery)
            ->orderBy($sort, $direction)
            ->paginate(15, ['*'], 'apps_page')
            ->appends(array_merge($baseAppends, ['tab' => 'applications']));

        $pendingVerificationsQuery = User::query()
            ->whereNull('email_verified_at')
            ->when($q, fn($qb) => $qb->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%");
            }));

        $pendingVerifications = (clone $pendingVerificationsQuery)
            ->orderBy($sort, $direction)
            ->paginate(15, ['*'], 'pending_page')
            ->appends(array_merge($baseAppends, ['tab' => 'pending_verification']));

        $counts = [
            'users' => User::where('role', 'buyer')->count(),
            'sellers' => User::where('role', 'seller')->count(),
            'apps' => User::where('seller_status', 'pending')->count(),
            'pending_verification' => User::whereNull('email_verified_at')->count(),
        ];

        return view('admin.users.index', compact('tab', 'regularUsers', 'sellers', 'applications', 'pendingVerifications', 'counts', 'q', 'sort', 'direction'));
    }

    // ─── 2. USER ACTIONS ─────────────────────────────────────────────────────

    public function updateUserStatus(Request $request, User $user): RedirectResponse
    {
        if ($user->role === 'admin' && $user->id !== Auth::id()) {
            return back()->withErrors(['status' => 'Tidak dapat mengubah status akun admin lain.']);
        }

        $messages = [
            'status.required' => 'Status akun wajib dipilih.',
            'status.in' => 'Status akun tidak valid.',
            'suspend_reason.string' => 'Alasan suspend harus berupa teks.',
            'suspend_reason.max' => 'Alasan suspend maksimal 1000 karakter.',
        ];

        $data = $request->validate([
            'status' => ['required', 'in:active,suspended'],
            'suspend_reason' => ['nullable', 'string', 'max:1000'],
        ], $messages);

        $updates = [
            'status' => $data['status'],
            'suspended_at' => $data['status'] === 'suspended' ? now() : null,
            'suspend_reason' => $data['status'] === 'suspended' ? ($data['suspend_reason'] ?? null) : null,
        ];

        $user->forceFill($updates)->save();

        if ($data['status'] === 'suspended') {
            DB::table('sessions')->where('user_id', $user->id)->delete();
        }

        return back()->with('success', "Status akun {$user->name} berhasil diperbarui.");
    }

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
        $messages = [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
            'rejection_reason.string' => 'Alasan penolakan harus berupa teks.',
            'rejection_reason.min' => 'Alasan penolakan minimal 10 karakter.',
            'rejection_reason.max' => 'Alasan penolakan maksimal 1000 karakter.',
        ];

        $data = $request->validate([
            'rejection_reason' => ['required', 'string', 'min:10', 'max:1000'],
        ], $messages);

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

    public function destroyUser(User $user): RedirectResponse
    {
        if ($user->role === 'admin') return back()->withErrors(['delete' => 'Admin tidak bisa dihapus.']);
        $user->delete();
        return back()->with('success', 'User dihapus.');
    }

    // ─── 3. BANNERS (SUDAH DIPERBAIKI!) ──────────────────────────────────────

    public function banners(): View
    {
        $banners = Schema::hasTable('banners') ? Banner::query()->latest()->get() : collect();
        return view('admin.banners.index', compact('banners'));
    }

    public function storeBanner(Request $request): RedirectResponse
    {
        $messages = [
            'title.required' => 'Judul banner wajib diisi.',
            'title.string' => 'Judul harus berupa teks.',
            'title.max' => 'Judul maksimal 255 karakter.',
            'subtitle.string' => 'Subtitle harus berupa teks.',
            'subtitle.max' => 'Subtitle maksimal 255 karakter.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus JPEG, PNG, JPG, GIF, atau WebP.',
            'image.max' => 'Ukuran gambar maksimal 5MB.',
            'image_url.url' => 'URL gambar tidak valid.',
            'image_url.max' => 'URL gambar maksimal 2048 karakter.',
            'link_url.url' => 'Link URL tidak valid.',
            'link_url.max' => 'Link URL maksimal 2048 karakter.',
            'position.required' => 'Posisi banner wajib dipilih.',
            'position.in' => 'Posisi banner tidak valid.',
            'is_active.boolean' => 'Status aktif harus berupa boolean.',
        ];

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'image_url' => ['nullable', 'url', 'max:2048'],
            'link_url' => ['nullable', 'url', 'max:2048'],
            'position' => ['required', 'in:hero,featured'],
            'is_active' => ['nullable', 'boolean'],
        ], $messages);

        $imagePath = null;
        $imageUrl = $data['image_url'] ?? null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'banner_' . time() . '.' . $file->getClientOriginalExtension();
            // Pakai disk 'public_app_public' sesuai kodingan lama kamu
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
        if ($banner->image_path) {
            Storage::disk('public_app_public')->delete($banner->image_path);
        }
        $banner->delete();
        return back()->with('success', 'Banner berhasil dihapus.');
    }

    // ─── 4. NOTIFICATIONS & ORDERS ───────────────────────────────────────────

    public function notifications(Request $request): View
    {
        $broadcasts = NotificationBroadcast::query()
            ->withCount('deliveries')
            ->latest()
            ->paginate(20);

        return view('admin.notifications.index', ['notifications' => $broadcasts]);
    }

    public function sendNotification(Request $request): RedirectResponse
    {
        $messages = [
            'audience.required' => 'Audiens notifikasi wajib dipilih.',
            'audience.in' => 'Audiens notifikasi tidak valid.',
            'category.required' => 'Kategori notifikasi wajib dipilih.',
            'category.in' => 'Kategori notifikasi tidak valid.',
            'title.required' => 'Judul notifikasi wajib diisi.',
            'title.string' => 'Judul harus berupa teks.',
            'title.max' => 'Judul maksimal 255 karakter.',
            'body.required' => 'Isi notifikasi wajib diisi.',
            'body.string' => 'Isi harus berupa teks.',
            'body.max' => 'Isi notifikasi maksimal 2000 karakter.',
            'link.url' => 'Link notifikasi tidak valid.',
            'link.max' => 'Link maksimal 2048 karakter.',
        ];

        $data = $request->validate([
            'audience' => ['required', 'in:all,buyer,seller'],
            'category' => ['required', 'in:event_reward,general'],
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:2000'],
            'link' => ['nullable', 'url', 'max:2048'],
        ], $messages);

        $users = User::query()
            ->when($data['audience'] !== 'all', fn($q) => $q->where('role', $data['audience']))
            ->get();

        $broadcast = NotificationBroadcast::create([
            'title' => $data['title'],
            'body' => $data['body'],
            'link' => $data['link'] ?? null,
            'type' => $data['category'] === MarketplaceNotification::CATEGORY_EVENT_REWARD
                ? 'admin-event_reward'
                : 'admin-general',
            'metadata' => [
                'category' => $data['category'],
                'audience' => $data['audience'],
            ],
        ]);

        foreach ($users as $user) {
            MarketplaceNotification::create([
                'user_id' => $user->id,
                'broadcast_id' => $broadcast->id,
                'type' => $broadcast->type,
            ]);
        }

        return back()->with('success', 'Notifikasi berhasil dikirim ke ' . $users->count() . ' akun.');
    }

    public function orders(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $sort = $request->query('sort', 'created_at');
        $direction = strtolower($request->query('direction', 'desc')) === 'asc' ? 'asc' : 'desc';

        $allowedSorts = ['created_at', 'order_code', 'grand_total'];
        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'created_at';
        }

        $ordersQuery = Order::query()->with(['buyer', 'seller', 'financial'])
            ->when($q, function ($qBuilder) use ($q) {
                $qBuilder->where('order_code', 'like', "%{$q}%")
                    ->orWhereHas('buyer', fn($b) => $b->where('name', 'like', "%{$q}%"))
                    ->orWhereHas('seller', fn($s) => $s->where('name', 'like', "%{$q}%"));
            });

        if ($sort === 'grand_total') {
            $ordersQuery->orderBy(
                OrderFinancial::select('grand_total')
                    ->whereColumn('order_financials.order_id', 'orders.id')
                    ->limit(1),
                $direction
            );
        } else {
            $ordersQuery->orderBy($sort, $direction);
        }

        $orders = $ordersQuery->paginate(20)->appends(array_filter(['q' => $q, 'sort' => $sort, 'direction' => $direction]));

        return view('admin.orders.index', compact('orders', 'q', 'sort', 'direction'));
    }

    public function downloadOrdersReportPdf()
    {
        if (! Schema::hasTable('orders')) {
            return back()->withErrors([
                'pdf' => 'Tabel pesanan belum tersedia, jadi laporan PDF belum bisa dibuat.',
            ]);
        }

        try {
            $orders = Order::query()
                ->with(['buyer:id,name', 'seller:id,name', 'financial:order_id,grand_total'])
                ->orderByDesc('created_at')
                ->get();

            return app(PdfDocumentService::class)->downloadOrdersReport($orders, 'laporan-pesanan.pdf');
        } catch (\Throwable $exception) {
            report($exception);

            return back()->withErrors([
                'pdf' => 'Gagal membuat laporan PDF. Silakan coba lagi setelah database dan relasi pesanan siap.',
            ]);
        }
    }

    public function showOrder(Order $order): View
    {
        $order->load(['buyer', 'seller', 'items.product', 'financial']);
        return view('admin.orders.show', compact('order'));
    }
}
