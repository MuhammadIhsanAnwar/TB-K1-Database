<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClarificationMessage;
use App\Models\MarketplaceNotification;
use App\Models\User;
use App\Models\VerificationLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VerificationController extends Controller
{
    // ── 1. Pending Verification Index ────────────────────────────────────────

    public function index(Request $request): View
    {
        $tab    = $request->query('tab', 'pending');
        $search = $request->query('search', '');

        $base = User::query()
            ->when($search, fn ($q) => $q->where(function ($q2) use ($search) {
                $q2->where('name', 'like', "%{$search}%")
                   ->orWhere('email', 'like', "%{$search}%")
                   ->orWhere('shop_name', 'like', "%{$search}%");
            }))
            ->orderByDesc('seller_submitted_at');

        $pending      = (clone $base)->where('seller_status', 'pending')->paginate(15, ['*'], 'pending_page');
        $underReview  = (clone $base)->where('seller_status', 'under_review')->paginate(15, ['*'], 'review_page');
        $needRevision = (clone $base)->where('seller_status', 'need_revision')->paginate(15, ['*'], 'revision_page');
        $approved     = (clone $base)->where('seller_status', 'approved')->paginate(15, ['*'], 'approved_page');
        $rejected     = (clone $base)->where('seller_status', 'rejected')->paginate(15, ['*'], 'rejected_page');
        $suspended    = (clone $base)->where('seller_status', 'suspended')->paginate(15, ['*'], 'suspended_page');

        $counts = [
            'pending'      => User::where('seller_status', 'pending')->count(),
            'under_review' => User::where('seller_status', 'under_review')->count(),
            'need_revision' => User::where('seller_status', 'need_revision')->count(),
            'approved'     => User::where('seller_status', 'approved')->count(),
            'rejected'     => User::where('seller_status', 'rejected')->count(),
            'suspended'    => User::where('seller_status', 'suspended')->count(),
        ];

        return view('admin.verification.index', compact(
            'tab', 'search',
            'pending', 'underReview', 'needRevision',
            'approved', 'rejected', 'suspended',
            'counts'
        ));
    }

    // ── 2. Show Individual Application ──────────────────────────────────────

    public function show(User $user): View
    {
        $logs = VerificationLog::query()
            ->where('user_id', $user->id)
            ->with('admin')
            ->latest()
            ->get();

        $clarifications = ClarificationMessage::query()
            ->where('user_id', $user->id)
            ->with('sender')
            ->latest()
            ->get();

        // Mark admin-sent messages as read when admin opens the page
        ClarificationMessage::query()
            ->where('user_id', $user->id)
            ->where('sender_type', 'user')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('admin.verification.show', compact('user', 'logs', 'clarifications'));
    }

    // ── 3. Actions ────────────────────────────────────────────────────────────

    /** Mark application as "Under Review" */
    public function markUnderReview(User $user): RedirectResponse
    {
        if ($user->seller_status !== 'pending') {
            return back()->withErrors(['action' => 'Status tidak valid untuk tindakan ini.']);
        }

        $user->forceFill([
            'seller_status'       => 'under_review',
            'seller_reviewed_at'  => now(),
        ])->save();

        $this->log($user, 'under_review', 'Admin mulai mereview pengajuan.');
        $this->notify($user, 'Pengajuan Seller Sedang Direview', 'Pengajuan toko Anda sedang kami review. Kami akan segera menghubungi Anda jika ada informasi tambahan yang diperlukan.', 'seller-review');

        return back()->with('success', "Pengajuan {$user->name} ditandai sebagai Under Review.");
    }

    /** Request revision / klarifikasi */
    public function requestRevision(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'revision_notes'       => ['required', 'string', 'min:10', 'max:2000'],
            'clarification_message' => ['nullable', 'string', 'max:2000'],
        ]);

        $user->forceFill([
            'seller_status'            => 'need_revision',
            'seller_rejection_reason'  => $data['revision_notes'],
            'seller_reviewed_at'       => now(),
        ])->save();

        $this->log($user, 'revision_requested', $data['revision_notes']);

        // Create a clarification message thread entry
        ClarificationMessage::create([
            'user_id'     => $user->id,
            'sender_id'   => Auth::id(),
            'sender_type' => 'admin',
            'message'     => $data['clarification_message'] ?? $data['revision_notes'],
        ]);

        $this->notify(
            $user,
            'Revisi Pengajuan Seller Diperlukan',
            "Pengajuan toko Anda memerlukan revisi. Alasan: {$data['revision_notes']}. Silakan login dan periksa detail klarifikasi.",
            'seller-revision',
            route('seller.verification.status')
        );

        return back()->with('success', "Permintaan revisi dikirim ke {$user->name}.");
    }

    /** Approve seller application */
    public function approve(User $user): RedirectResponse
    {
        $allowedStatuses = ['pending', 'under_review', 'need_revision'];
        if (! in_array($user->seller_status, $allowedStatuses, true)) {
            return back()->withErrors(['action' => 'Pengajuan tidak dalam status yang valid untuk disetujui.']);
        }

        $user->forceFill([
            'role'                     => 'seller',
            'seller_status'            => 'approved',
            'seller_rejection_reason'  => null,
            'status'                   => 'active',
            'is_seller'                => true,
            'seller_reviewed_at'       => now(),
        ])->save();

        $this->log($user, 'approved', 'Pengajuan seller disetujui oleh admin.');
        $this->notify(
            $user,
            '🎉 Selamat! Pengajuan Seller Disetujui',
            "Toko \"{$user->shop_name}\" Anda telah berhasil diverifikasi. Anda sekarang bisa mulai berjualan di Lapak Gaming!",
            'seller-approved',
            route('seller.dashboard')
        );

        return back()->with('success', "Pengajuan seller {$user->name} berhasil disetujui.");
    }

    /** Reject seller application */
    public function reject(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'rejection_reason' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        $user->forceFill([
            'seller_status'           => 'rejected',
            'seller_rejection_reason' => $data['rejection_reason'],
            'seller_reviewed_at'      => now(),
        ])->save();

        $this->log($user, 'rejected', $data['rejection_reason']);

        // Also create a clarification message for rejected
        ClarificationMessage::create([
            'user_id'     => $user->id,
            'sender_id'   => Auth::id(),
            'sender_type' => 'admin',
            'message'     => "Pengajuan Anda ditolak. Alasan: {$data['rejection_reason']}",
        ]);

        $this->notify(
            $user,
            'Pengajuan Seller Ditolak',
            "Maaf, pengajuan toko Anda ditolak. Alasan: {$data['rejection_reason']}. Anda dapat mengajukan ulang setelah memperbaiki dokumen.",
            'seller-rejected',
            route('seller.verification.status')
        );

        return back()->with('success', "Pengajuan seller {$user->name} ditolak.");
    }

    /** Suspend a seller account */
    public function suspend(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'suspend_reason' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        $user->forceFill([
            'seller_status'  => 'suspended',
            'suspended_at'   => now(),
            'suspend_reason' => $data['suspend_reason'],
        ])->save();

        \DB::table('sessions')->where('user_id', $user->id)->delete();

        $this->log($user, 'suspended', $data['suspend_reason']);
        $this->notify(
            $user,
            'Akun Seller Anda Disuspend',
            "Akun seller Anda telah disuspend sementara. Alasan: {$data['suspend_reason']}. Hubungi admin untuk informasi lebih lanjut.",
            'seller-suspended'
        );

        return back()->with('success', "Akun seller {$user->name} berhasil disuspend.");
    }

    /** Reinstate a suspended seller */
    public function reinstate(User $user): RedirectResponse
    {
        if ($user->seller_status !== 'suspended') {
            return back()->withErrors(['action' => 'Akun ini tidak dalam status suspended.']);
        }

        $user->forceFill([
            'seller_status'  => 'approved',
            'suspended_at'   => null,
            'suspend_reason' => null,
            'status'         => 'active',
        ])->save();

        $this->log($user, 'reinstated', 'Akun seller dipulihkan oleh admin.');
        $this->notify(
            $user,
            'Akun Seller Anda Dipulihkan',
            'Akun seller Anda telah dipulihkan. Anda dapat kembali berjualan di Lapak Gaming.',
            'seller-reinstated',
            route('seller.dashboard')
        );

        return back()->with('success', "Akun seller {$user->name} berhasil dipulihkan.");
    }

    /** Admin sends a klarifikasi message */
    public function sendClarification(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'min:5', 'max:2000'],
        ]);

        ClarificationMessage::create([
            'user_id'     => $user->id,
            'sender_id'   => Auth::id(),
            'sender_type' => 'admin',
            'message'     => $data['message'],
        ]);

        $this->notify(
            $user,
            'Pesan Klarifikasi Baru dari Admin',
            'Admin mengirim pesan baru mengenai pengajuan seller Anda. Silakan login untuk melihat.',
            'clarification',
            route('seller.verification.status')
        );

        return back()->with('success', 'Pesan klarifikasi berhasil dikirim.');
    }

    // ── Private Helpers ────────────────────────────────────────────────────

    private function log(User $user, string $action, ?string $notes = null): void
    {
        VerificationLog::create([
            'user_id'  => $user->id,
            'admin_id' => Auth::id(),
            'action'   => $action,
            'notes'    => $notes,
        ]);
    }

    private function notify(User $user, string $title, string $body, string $type, ?string $link = null): void
    {
        MarketplaceNotification::create([
            'user_id' => $user->id,
            'title'   => $title,
            'body'    => $body,
            'link'    => $link,
            'type'    => $type,
        ]);
    }
}