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
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class VerificationController extends Controller
{
    // ─── Index: tabbed list ──────────────────────────────────────────────────

    public function index(Request $request): View
    {
        $tab = $request->query('tab', 'pending');

        $pendingQ      = User::where('seller_status', 'pending');
        $reviewQ       = User::where('seller_status', 'under_review');
        $revisionQ     = User::where('seller_status', 'need_revision');
        $approvedQ     = User::where('seller_status', 'approved');
        $rejectedQ     = User::whereIn('seller_status', ['rejected', 'suspended']);

        $counts = [
            'pending'      => (clone $pendingQ)->count(),
            'under_review' => (clone $reviewQ)->count(),
            'need_revision'=> (clone $revisionQ)->count(),
            'approved'     => (clone $approvedQ)->count(),
            'rejected'     => (clone $rejectedQ)->count(),
        ];

        $users = match($tab) {
            'under_review'  => $reviewQ->latest()->paginate(15)->appends(['tab' => $tab]),
            'need_revision' => $revisionQ->latest()->paginate(15)->appends(['tab' => $tab]),
            'approved'      => $approvedQ->latest()->paginate(15)->appends(['tab' => $tab]),
            'rejected'      => $rejectedQ->latest()->paginate(15)->appends(['tab' => $tab]),
            default         => $pendingQ->latest()->paginate(15)->appends(['tab' => $tab]),
        };

        return view('admin.verification.index', compact('tab', 'users', 'counts'));
    }

    // ─── Show detail ─────────────────────────────────────────────────────────

    public function show(User $user): View
    {
        $logs = VerificationLog::where('user_id', $user->id)
            ->with('admin')
            ->latest()
            ->get();

        $clarifications = ClarificationMessage::where('user_id', $user->id)
            ->with('sender')
            ->latest()
            ->get();

        // Mark all unread clarifications as read (admin opened this page)
        ClarificationMessage::where('user_id', $user->id)
            ->where('sender_type', 'user')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('admin.verification.show', compact('user', 'logs', 'clarifications'));
    }

    // ─── Action: Mark Under Review ────────────────────────────────────────────

    public function markUnderReview(Request $request, User $user): RedirectResponse
    {
        if (! in_array($user->seller_status, ['pending', 'need_revision'])) {
            return back()->with('error', 'Status tidak valid untuk tindakan ini.');
        }

        $user->forceFill([
            'seller_status'       => 'under_review',
            'seller_reviewed_at'  => now(),
        ])->save();

        VerificationLog::create([
            'user_id'  => $user->id,
            'admin_id' => Auth::id(),
            'action'   => 'under_review',
            'notes'    => 'Admin mulai mereview pengajuan.',
        ]);

        $this->notify($user, 'Pengajuan Sedang Direview', 
            'Admin sedang mereview pengajuan seller toko Anda. Kami akan segera memberikan keputusan.',
            'seller-under-review');

        return back()->with('success', "Pengajuan {$user->name} ditandai sedang direview.");
    }

    // ─── Action: Request Revision ─────────────────────────────────────────────

    public function requestRevision(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'notes'   => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        $user->forceFill([
            'seller_status'           => 'need_revision',
            'seller_reviewed_at'      => now(),
        ])->save();

        VerificationLog::create([
            'user_id'  => $user->id,
            'admin_id' => Auth::id(),
            'action'   => 'revision_requested',
            'notes'    => $data['notes'],
        ]);

        ClarificationMessage::create([
            'user_id'     => $user->id,
            'sender_id'   => Auth::id(),
            'sender_type' => 'admin',
            'message'     => $data['notes'],
        ]);

        $this->notify($user, 'Revisi Diperlukan', 
            'Pengajuan seller Anda memerlukan revisi. Silakan cek pesan klarifikasi untuk detail.',
            'seller-need-revision');

        return back()->with('success', "Permintaan revisi terkirim ke {$user->name}.");
    }

    // ─── Action: Approve ──────────────────────────────────────────────────────

    public function approve(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $user->forceFill([
            'seller_status'           => 'approved',
            'role'                    => 'seller',
            'is_seller'               => true,
            'status'                  => 'active',
            'seller_rejection_reason' => null,
            'seller_reviewed_at'      => now(),
        ])->save();

        VerificationLog::create([
            'user_id'  => $user->id,
            'admin_id' => Auth::id(),
            'action'   => 'approved',
            'notes'    => $data['notes'] ?? 'Pengajuan disetujui.',
        ]);

        $this->notify($user, 'Selamat! Pengajuan Seller Disetujui 🎉',
            "Toko \"{$user->shop_name}\" Anda telah diverifikasi. Mulai berjualan sekarang!",
            'seller-approved',
            route('seller.dashboard'));

        return back()->with('success', "Pengajuan seller {$user->name} berhasil disetujui.");
    }

    // ─── Action: Reject ───────────────────────────────────────────────────────

    public function reject(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'notes' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        $user->forceFill([
            'seller_status'           => 'rejected',
            'seller_rejection_reason' => $data['notes'],
            'seller_reviewed_at'      => now(),
        ])->save();

        VerificationLog::create([
            'user_id'  => $user->id,
            'admin_id' => Auth::id(),
            'action'   => 'rejected',
            'notes'    => $data['notes'],
        ]);

        ClarificationMessage::create([
            'user_id'     => $user->id,
            'sender_id'   => Auth::id(),
            'sender_type' => 'admin',
            'message'     => "Pengajuan Anda ditolak.\n\nAlasan: " . $data['notes'],
        ]);

        $this->notify($user, 'Pengajuan Seller Ditolak',
            "Maaf, pengajuan toko Anda ditolak. Silakan periksa pesan klarifikasi untuk alasan lengkap.",
            'seller-rejected',
            route('seller.register.form'));

        return back()->with('success', "Pengajuan seller {$user->name} ditolak.");
    }

    // ─── Action: Suspend ──────────────────────────────────────────────────────

    public function suspend(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'notes' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        $user->forceFill([
            'seller_status'  => 'suspended',
            'suspended_at'   => now(),
            'suspend_reason' => $data['notes'],
        ])->save();

        \DB::table('sessions')->where('user_id', $user->id)->delete();

        VerificationLog::create([
            'user_id'  => $user->id,
            'admin_id' => Auth::id(),
            'action'   => 'suspended',
            'notes'    => $data['notes'],
        ]);

        $this->notify($user, 'Akun Seller Disuspend',
            "Akun seller Anda telah disuspend. Alasan: {$data['notes']}",
            'seller-suspended');

        return back()->with('success', "Akun seller {$user->name} berhasil disuspend.");
    }

    // ─── Action: Reinstate ────────────────────────────────────────────────────

    public function reinstate(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $user->forceFill([
            'seller_status' => 'approved',
            'suspended_at'  => null,
            'suspend_reason'=> null,
            'status'        => 'active',
        ])->save();

        VerificationLog::create([
            'user_id'  => $user->id,
            'admin_id' => Auth::id(),
            'action'   => 'reinstated',
            'notes'    => $data['notes'] ?? 'Akun dipulihkan oleh admin.',
        ]);

        $this->notify($user, 'Akun Seller Dipulihkan',
            "Akun seller Anda telah dipulihkan. Anda dapat berjualan kembali.",
            'seller-reinstated',
            route('seller.dashboard'));

        return back()->with('success', "Akun seller {$user->name} berhasil dipulihkan.");
    }

    // ─── Action: Send Clarification ───────────────────────────────────────────

    public function sendClarification(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'message'     => ['required', 'string', 'min:5', 'max:2000'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'max:5120'],
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('clarifications/' . $user->id, 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'url'  => Storage::url($path),
                ];
            }
        }

        ClarificationMessage::create([
            'user_id'     => $user->id,
            'sender_id'   => Auth::id(),
            'sender_type' => 'admin',
            'message'     => $data['message'],
            'attachments' => $attachments ?: null,
        ]);

        $this->notify($user, 'Pesan Baru dari Admin',
            'Admin mengirim pesan klarifikasi untuk pengajuan seller Anda.',
            'clarification');

        return back()->with('success', 'Pesan klarifikasi terkirim.');
    }

    // ─── Private: send notification ──────────────────────────────────────────

    private function notify(User $user, string $title, string $body, string $type, ?string $link = null): void
    {
        MarketplaceNotification::create([
            'user_id' => $user->id,
            'title'   => $title,
            'body'    => $body,
            'link'    => $link ?? route('buyer.dashboard'),
            'type'    => $type,
        ]);
    }
}