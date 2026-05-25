<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'subject',
        'category',
        'message',
        'admin_reply',
        'replied_by',
        'replied_at',
        'read_at',
        'status',
        'metadata',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
        'read_at' => 'datetime',
        'metadata' => 'array',
    ];

    protected $appends = [
        'category_label',
        'status_label',
        'is_replied',
        'is_read',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'replied_by');
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'payment' => 'Top Up & Pembayaran',
            'account' => 'Akun',
            'fraud' => 'Fraud / Penipuan',
            default => 'Umum',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'replied' => 'Sudah Dibalas',
            'read' => 'Sudah Dibaca',
            default => 'Baru',
        };
    }

    public function getIsRepliedAttribute(): bool
    {
        return $this->replied_at !== null || filled($this->admin_reply);
    }

    public function getIsReadAttribute(): bool
    {
        return $this->read_at !== null;
    }

    public function markRead(): void
    {
        if (! $this->read_at) {
            $this->forceFill(['read_at' => now(), 'status' => $this->status === 'new' ? 'read' : $this->status])->save();
        }
    }

    public function markReplied(?int $adminId = null): void
    {
        $this->forceFill([
            'status' => 'replied',
            'replied_at' => now(),
            'replied_by' => $adminId,
        ])->save();
    }
}