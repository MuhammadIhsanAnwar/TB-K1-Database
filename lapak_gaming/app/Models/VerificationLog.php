<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerificationLog extends Model
{
    protected $fillable = [
        'user_id',
        'admin_id',
        'action',
        'notes',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    // ── Relations ──────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // ── Helpers ────────────────────────────────────────────────────────────

    /** Human-readable action label for timeline display. */
    public function actionLabel(): string
    {
        return match ($this->action) {
            'submitted'           => 'Pengajuan Dikirim',
            'under_review'        => 'Sedang Direview',
            'revision_requested'  => 'Diminta Revisi',
            'approved'            => 'Disetujui',
            'rejected'            => 'Ditolak',
            'suspended'           => 'Akun Disuspend',
            'resubmitted'         => 'Diajukan Ulang',
            'reinstated'          => 'Akun Dipulihkan',
            default               => ucfirst($this->action),
        };
    }

    /** Tailwind colour tokens per action for the timeline badge. */
    public function actionColor(): string
    {
        return match ($this->action) {
            'submitted', 'resubmitted' => 'blue',
            'under_review'             => 'yellow',
            'revision_requested'       => 'orange',
            'approved', 'reinstated'   => 'green',
            'rejected', 'suspended'    => 'red',
            default                    => 'slate',
        };
    }
}