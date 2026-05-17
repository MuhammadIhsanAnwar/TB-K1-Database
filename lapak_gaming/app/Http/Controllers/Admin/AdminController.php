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
use Barryvdh\DomPDF\Facade\Pdf;

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

        foreach ($users as $user) {
            MarketplaceNotification::create([
                'user_id' => $user->id,
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
        }

        return back()->with('success', 'Notifikasi berhasil dikirim ke ' . $users->count() . ' akun.');
    }

    public function orders(Request $request): View
    {
        $orders = Order::query()->with(['buyer', 'seller'])->latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

   public function downloadOrdersReportPdf()
{
    $orders = Order::with(['buyer', 'seller'])
        ->latest()
        ->get();

    $pdf = Pdf::loadView('admin.pdf.orders-report', [
        'orders' => $orders
    ]);

    return $pdf->download('laporan-pesanan.pdf');
}

    public function showOrder(Order $order): View
    {
        $order->load(['buyer', 'seller', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }

    private function pdfColumn(?string $value, int $length): string
    {
        $value = preg_replace('/\s+/', ' ', (string) $value);
        $value = $this->safeSubstr($value, $length);

        return str_pad($value, $length);
    }

    private function reportOrderTotal(Order $order): float
    {
        if (Schema::hasTable('order_financials')) {
            return (float) $order->grand_total;
        }

        return (float) ($order->getAttributes()['grand_total'] ?? 0);
    }

    private function buildSimplePdf(array $pages): string
    {
        $objects = [];
        $objects[1] = '<< /Type /Catalog /Pages 2 0 R >>';
        $objects[3] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>';
        $pageIds = [];
        $nextId = 4;

        foreach ($pages as $pageLines) {
            $stream = '';
            $y = 800;

            foreach ($pageLines as $line) {
                $text = $this->pdfEscape((string) ($line['text'] ?? ''));
                $size = (int) ($line['size'] ?? 10);
                $stream .= "BT /F1 {$size} Tf 40 {$y} Td ({$text}) Tj ET\n";
                $y -= $size >= 14 ? 24 : 15;
            }

            $contentId = $nextId++;
            $objects[$contentId] = '<< /Length ' . strlen($stream) . " >>\nstream\n" . $stream . "endstream";

            $pageId = $nextId++;
            $objects[$pageId] = "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 3 0 R >> >> /Contents {$contentId} 0 R >>";
            $pageIds[] = $pageId;
        }

        $kids = collect($pageIds)->map(fn ($id) => "{$id} 0 R")->implode(' ');
        $objects[2] = "<< /Type /Pages /Kids [{$kids}] /Count " . count($pageIds) . ' >>';
        ksort($objects);

        $pdf = "%PDF-1.4\n";
        $offsets = [0 => 0];

        foreach ($objects as $id => $body) {
            $offsets[$id] = strlen($pdf);
            $pdf .= "{$id} 0 obj\n{$body}\nendobj\n";
        }

        $xrefOffset = strlen($pdf);
        $maxId = max(array_keys($objects));
        $pdf .= "xref\n0 " . ($maxId + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";

        for ($i = 1; $i <= $maxId; $i++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$i] ?? 0);
        }

        $pdf .= "trailer\n<< /Size " . ($maxId + 1) . " /Root 1 0 R >>\n";
        $pdf .= "startxref\n{$xrefOffset}\n%%EOF";

        return $pdf;
    }

    private function pdfEscape(string $value): string
    {
        $value = $this->normalizePdfText($value);
        $value = str_replace(["\\", "(", ")", "\r", "\n"], ["\\\\", "\\(", "\\)", ' ', ' '], $value);

        return $this->safeSubstr($value, 130);
    }

    private function safeSubstr(string $value, int $length): string
    {
        if (function_exists('mb_substr')) {
            return mb_substr($value, 0, $length);
        }

        return substr($value, 0, $length);
    }

    private function normalizePdfText(string $value): string
    {
        // Keep simple PDF text stream stable by converting UTF-8 to a Latin-1 compatible range.
        if (function_exists('iconv')) {
            $converted = @iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $value);
            if ($converted !== false) {
                $value = $converted;
            }
        }

        return preg_replace('/[^\x20-\x7E\xA0-\xFF]/', '', $value) ?? '';
    }
}
