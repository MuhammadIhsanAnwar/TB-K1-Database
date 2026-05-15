
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'seller_id',
        'last_message_at',
        'last_message_text',
        'unread_buyer',
        'unread_seller',
        'buyer_typing_at',
        'seller_typing_at',
        'buyer_last_seen_at',
        'seller_last_seen_at',
    ];

    protected $casts = [
        'last_message_at'     => 'datetime',
        'buyer_typing_at'     => 'datetime',
        'seller_typing_at'    => 'datetime',
        'buyer_last_seen_at'  => 'datetime',
        'seller_last_seen_at' => 'datetime',
        'unread_buyer'        => 'integer',
        'unread_seller'       => 'integer',
    ];

    // ── Relations ──────────────────────────────────────────────────────────

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    // ── Helpers ────────────────────────────────────────────────────────────

    /**
     * Mendapatkan lawan bicara berdasarkan user yang sedang login.
     */
    public function getPartner(int $authId)
    {
        return $this->buyer_id === $authId ? $this->seller : $this->buyer;
    }

    /**
 * Compatibility helper untuk blade lama.
 */
public function partner(int $authId)
{
    return $this->getPartner($authId);
}

/**
 * Mengambil unread count berdasarkan user login.
 */
public function unreadFor(int $authId): int
{
    return $this->buyer_id === $authId
        ? $this->unread_buyer
        : $this->unread_seller;
}
    /**
     * Cek apakah lawan bicara sedang mengetik (berlaku selama 5 detik terakhir).
     */
    public function isPartnerTyping(int $authId): bool
    {
        $typingAt = $this->buyer_id === $authId ? $this->seller_typing_at : $this->buyer_typing_at;
        return $typingAt && $typingAt->diffInSeconds(now()) < 5;
    }

    /**
     * Format ringkasan percakapan untuk list inbox.
     */
    public function toSummary(int $authId): array
    {
        $partner = $this->getPartner($authId);
        $isBuyer = $this->buyer_id === $authId;

        return [
            'id'                => $this->id,
            'partner_id'        => $partner?->id,
            'partner_name'      => $partner?->name ?? 'User',
            'partner_avatar'    => $partner?->avatar_url,
            'last_message'      => $this->last_message_text,
            'last_message_time' => $this->last_message_at?->diffForHumans(),
            'unread_count'      => $isBuyer ? $this->unread_buyer : $this->unread_seller,
            'is_typing'         => $this->isPartnerTyping($authId),
        ];
    }
}
