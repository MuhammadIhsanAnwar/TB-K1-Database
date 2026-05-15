<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'order_id',
        'product_id',
        'sender_id',
        'sender_role',
        'receiver_id',
        'message',
        'attachment_path',
        'attachment_type',
        'is_read',
        'read_at',
        'edited_at',
        'deleted_for_sender_at',
        'deleted_for_receiver_at',
        'deleted_for_everyone_at',
    ];

    protected $casts = [
        'is_read'                 => 'boolean',
        'read_at'                 => 'datetime',
        'edited_at'               => 'datetime',
        'deleted_for_sender_at'   => 'datetime',
        'deleted_for_receiver_at' => 'datetime',
        'deleted_for_everyone_at' => 'datetime',
    ];

    // ── Boot Logic ──────────────────────────────────────────────────────────

    protected static function boot()
    {
        parent::boot();

        // Otomatis update percakapan induk saat ada pesan baru
        static::created(function ($message) {
            $conversation = $message->conversation;
            if ($conversation) {
                $updateData = [
                    'last_message_at' => $message->created_at,
                    'last_message'    => $message->attachment_path ? '[Lampiran]' : $message->message,
                ];

                // Update counter unread untuk penerima
                if ($message->receiver_id === $conversation->buyer_id) {
                    $conversation->increment('unread_buyer');
                } else {
                    $conversation->increment('unread_seller');
                }

                $conversation->update($updateData);
            }
        });
    }

    // ── Relations ──────────────────────────────────────────────────────────

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
    public function getImageUrlAttribute()
    {
        return $this->attachment_path ? asset('storage/' . $this->attachment_path) : null;
    }
    // ── Helpers ────────────────────────────────────────────────────────────

    public function isDeletedForEveryone(): bool
    {
        return $this->deleted_for_everyone_at !== null;
    }

    public function isHiddenFor(int $userId): bool
    {
        if ($this->isDeletedForEveryone()) return true;
        if ($userId === $this->sender_id && $this->deleted_for_sender_at !== null) return true;
        if ($userId === $this->receiver_id && $this->deleted_for_receiver_at !== null) return true;
        return false;
    }

    public function toChat(int $authId): array
    {
        $isHidden = $this->isHiddenFor($authId);

        return [
            'id'               => $this->id,
            'conversation_id'  => $this->conversation_id,
            'sender_id'        => $this->sender_id,
            'sender_name'      => $this->sender?->name ?? 'User',
            'is_mine'          => $this->sender_id === $authId,
            'message'          => $isHidden ? ($this->isDeletedForEveryone() ? 'Pesan ini telah dihapus' : null) : $this->message,
            'is_deleted'       => $this->isDeletedForEveryone(),
            'attachment_path'  => $isHidden ? null : $this->attachment_path,
            'attachment_type'  => $this->attachment_type,
            'is_read'          => (bool) $this->is_read,
            'time'             => $this->created_at->format('H:i'),
            'created_at'       => $this->created_at->toIso8601String(),
        ];
    }
}

