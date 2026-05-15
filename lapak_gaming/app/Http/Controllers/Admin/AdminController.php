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

        $regularUsers = User::query()
            ->where('role', 'buyer')
            ->orderByDesc('created_at')
            ->paginate(15, ['*'], 'users_page')
            ->appends(['tab' => 'users']);

        $sellers = User::query()
            ->where('role', 'seller')
            ->orderByDesc('created_at')
            ->paginate(15, ['*'], 'sellers_page')
            ->appends(['tab' => 'sellers']);

        $applications = User::query()
            ->where('seller_status', 'pending')
            ->orderByDesc('created_at')
            ->paginate(15, ['*'], 'apps_page')
            ->appends(['tab' => 'applications']);

        $pendingVerifications = User::query()
            ->whereNull('email_verified_at')
            ->orderByDesc('created_at')
            ->paginate(15, ['*'], 'pending_page')
            ->appends(['tab' => 'pending_verification']);

        $counts = [
            'users' => User::where('role', 'buyer')->count(),
            'sellers' => User::where('role', 'seller')->count(),
            'apps' => User::where('seller_status', 'pending')->count(),
            'pending_verification' => User::whereNull('email_verified_at')->count(),
        ];

        return view('admin.users.index', compact('tab', 'regularUsers', 'sellers', 'applications', 'pendingVerifications', 'counts'));
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

    public function notifications(): View
    {
        $notifications = MarketplaceNotification::query()->latest()->paginate(20);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function sendNotification(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'audience' => ['required', 'in:all,buyer,seller'],
            'category' => ['required', 'in:event_reward,general'],
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
                'type' => $data['category'] === 'event_reward' ? 'admin-event_reward' : 'admin-general',
            ]);
        }

        return back()->with('success', 'Notifikasi terkirim.');
    }

    public function orders(Request $request): View
    {
        $orders = Order::query()->with(['buyer', 'seller'])->latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * VERSI BULLETPROOF: Anti Error 500
     */
    public function downloadOrdersReportPdf()
    {
        // 1. Ambil relasi dengan hati-hati (seller mungkin tidak ada langsung di order)
        $relations = ['buyer'];
        if (Schema::hasTable('order_financials')) { $relations[] = 'financial'; }

        $orders = Order::with($relations)->oldest()->get();

        if ($orders->isEmpty()) {
            return back()->with('error', 'Tidak ada data untuk dicetak.');
        }

        // 2. Hitung total dengan fungsi yang lebih aman
        $totalAmount = $orders->sum(fn ($o) => $this->reportOrderTotal($o));
        $generatedAt = now()->format('d M Y H:i');
        
        $statusCounts = $orders->groupBy('status')->map(fn ($items) => $items->count());

        // 3. Susun baris teks
        $orderLines = $orders->map(function ($order) {
            $invoice = $this->pdfColumn($order->invoice_number ?? $order->order_code ?? '-', 18);
            $buyer   = $this->pdfColumn($order->buyer?->name ?? 'User', 16);
            
            // Ambil seller dari relasi items kalau relasi seller langsung kosong
            $sellerName = $order->seller?->name ?? $order->items->first()?->product?->seller?->name ?? '-';
            $seller  = $this->pdfColumn($sellerName, 16);
            
            // Pakai status mentah kalau status_label gak ada
            $statusText = $order->status ?? 'pending';
            $status  = $this->pdfColumn(strtoupper($statusText), 18);
            
            $totalVal = $this->reportOrderTotal($order);
            $total   = str_pad('Rp ' . number_format($totalVal, 0, ',', '.'), 16, ' ', STR_PAD_LEFT);

            return "{$invoice} {$buyer} {$seller} {$status} {$total}";
        });

        // 4. Proses Halaman
        $pages = [];
        foreach ($orderLines->chunk(30) as $index => $chunk) {
            $lines = [
                ['text' => 'LAPORAN TRANSAKSI LAPAK GAMING', 'size' => 15],
                ['text' => 'Dicetak: ' . $generatedAt, 'size' => 9],
                ['text' => str_repeat('-', 95), 'size' => 9],
            ];

            if ($index === 0) {
                $lines[] = ['text' => "Total: " . number_format($orders->count()) . " Order | Rp " . number_format($totalAmount, 0, ',', '.'), 'size' => 10];
                $lines[] = ['text' => '', 'size' => 5];
            }

            $lines[] = ['text' => 'Invoice            Buyer            Seller           Status             Total', 'size' => 9];
            $lines[] = ['text' => str_repeat('-', 95), 'size' => 9];

            foreach ($chunk as $line) {
                $lines[] = ['text' => (string)$line, 'size' => 9];
            }
            $pages[] = $lines;
        }

        $pdf = $this->buildSimplePdf($pages);
        
        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="laporan-' . date('Ymd-His') . '.pdf"',
        ]);
    }

    public function showOrder(Order $order): View
    {
        $order->load(['buyer', 'seller', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }

    private function pdfColumn(?string $value, int $length): string
    {
        $clean = preg_replace('/\s+/', ' ', (string)($value ?? ''));
        if (function_exists('mb_substr')) {
            $clean = mb_substr($clean, 0, $length);
        } else {
            $clean = substr($clean, 0, $length);
        }
        return str_pad($clean, $length);
    }

    private function reportOrderTotal($order): float
    {
        // Cek semua kemungkinan kolom total biar gak null pointer
        return (float) ($order->financial?->grand_total ?? $order->grand_total ?? $order->total_price ?? 0);
    }

    private function buildSimplePdf(array $pages): string
    {
        $pdf = "%PDF-1.4\n";
        $objs = [];
        $objs[1] = "<< /Type /Catalog /Pages 2 0 R >>";
        $objs[3] = "<< /Type /Font /Subtype /Type1 /BaseFont /Courier >>"; // Pake Courier biar tabel lurus

        $pageIds = [];
        $nextId = 4;

        foreach ($pages as $pLines) {
            $stream = "";
            $y = 800;
            foreach ($pLines as $l) {
                $txt = str_replace(["\\", "(", ")"], ["\\\\", "\\(", "\\)"], $l['text'] ?? '');
                $sz = $l['size'] ?? 10;
                $stream .= "BT /F1 {$sz} Tf 40 {$y} Td ({$txt}) Tj ET\n";
                $y -= ($sz >= 14 ? 22 : 14);
            }

            $cId = $nextId++;
            $objs[$cId] = "<< /Length " . strlen($stream) . " >>\nstream\n" . $stream . "endstream";
            $pId = $nextId++;
            $objs[$pId] = "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 3 0 R >> >> /Contents {$cId} 0 R >>";
            $pageIds[] = $pId;
        }

        $kids = implode(' ', array_map(fn($id) => "{$id} 0 R", $pageIds));
        $objs[2] = "<< /Type /Pages /Kids [{$kids}] /Count " . count($pageIds) . " >>";
        ksort($objs);

        $offsets = [];
        foreach ($objs as $id => $body) {
            $offsets[$id] = strlen($pdf);
            $pdf .= "{$id} 0 obj\n{$body}\nendobj\n";
        }

        $xrefOff = strlen($pdf);
        $maxId = max(array_keys($objs));
        $pdf .= "xref\n0 " . ($maxId + 1) . "\n0000000000 65535 f \n";
        for ($i = 1; $i <= $maxId; $i++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$i] ?? 0);
        }
        $pdf .= "trailer\n<< /Size " . ($maxId + 1) . " /Root 1 0 R >>\nstartxref\n{$xrefOff}\n%%EOF";
        return $pdf;
    }
}