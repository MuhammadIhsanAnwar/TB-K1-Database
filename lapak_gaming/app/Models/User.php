<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\UserProfile;
use App\Models\UserPolicyConsent;
use App\Models\WalletTransaction;
use Illuminate\Auth\Notifications\VerifyEmail;
use Laravel\Jetstream\HasTeams;
use Laravel\Jetstream\HasProfilePhoto;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasTeams, HasProfilePhoto;

    protected $fillable = [
        'name', 'email', 'password', 'role',
        'status', 'seller_level_id', 'suspended_at', 'suspend_reason',
        'google_id', 'phone', 'avatar', 'profile_photo_path',
        'account_deletion_token', 'account_deletion_token_sent_at', 'deactivated_at',
        'two_factor_enabled', 'two_factor_methods', 'two_factor_google_secret', 'two_factor_confirmed_at',
        // Seller registration workflow
        'seller_status', 'seller_rejection_reason',
        // Shop profile
        'shop_name', 'shop_photo', 'shop_description',
        // Jetstream
        'current_team_id',
    ];

    protected $guarded = [];

    protected $appends = [
        'profile_photo_url',
    ];

    protected $casts = [
        'email_verified_at'           => 'datetime',
        'password'                    => 'hashed',
        'two_factor_enabled'          => 'boolean',
        'two_factor_methods'          => 'array',
        'deactivated_at'              => 'datetime',
        'account_deletion_token_sent_at' => 'datetime',
        'suspended_at'                => 'datetime',
        'last_login_at'               => 'datetime',
        'two_factor_confirmed_at'     => 'datetime',
    ];

    protected $hidden = ['password', 'remember_token', 'two_factor_google_secret'];

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeSeller($query)
    {
        return $query->where('role', 'seller');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /** Users who are approved sellers (role = seller OR is_seller = true). */
    public function scopeApprovedSellers($query)
    {
        return $query->where('role', 'seller')->where('seller_status', 'approved');
    }

    /** Users who have submitted a seller application pending admin review. */
    public function scopePendingSellerApplications($query)
    {
        return $query->where('seller_status', 'pending');
    }

    // ─── Relations ───────────────────────────────────────────────────────────

    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function soldItems()
    {
        return $this->hasMany(OrderItem::class, 'seller_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    public function transactions(): HasManyThrough
    {
        return $this->hasManyThrough(WalletTransaction::class, Wallet::class);
    }

    public function policyConsents(): HasMany
    {
        return $this->hasMany(UserPolicyConsent::class);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function isSeller(): bool
    {
        return $this->role === 'seller';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isBuyer(): bool
    {
        return $this->role === 'buyer';
    }

    public function isGoogleAccount(): bool
    {
        return ! empty($this->google_id);
    }

    public function isSellerAccount(): bool
    {
        return $this->role === 'seller' || ($this->attributes['seller_status'] ?? 'none') === 'approved';
    }

    /** Whether the user has a pending seller application awaiting admin review. */
    public function hasPendingSellerApplication(): bool
    {
        return ($this->attributes['seller_status'] ?? 'none') === 'pending';
    }

    /** Whether the user's seller application was approved. */
    public function isApprovedSeller(): bool
    {
        return ($this->attributes['seller_status'] ?? 'none') === 'approved';
    }

    public function hasRole(string $role): bool
    {
        // Role sistem: checkout butuh user dianggap buyer.
        // Berdasarkan alur project: tidak ada akun khusus seller, jadi user yang seller juga harus dianggap buyer.
        if ($role === 'buyer') {
            return $this->role === 'buyer' || $this->role === 'seller' || $this->isSellerAccount();
        }

        if ($role === 'seller') {
            return $this->isSellerAccount();
        }

        return $this->role === $role;
    }

    // ─── Attribute Accessors ─────────────────────────────────────────────────

    public function getAvatarUrlAttribute(): string
    {
        $avatarPath = $this->attributes['avatar'] ?? $this->profile?->avatar_path ?? null;

        return $avatarPath
            ? (filter_var($avatarPath, FILTER_VALIDATE_URL)
                ? $avatarPath
                : asset('storage/' . $avatarPath))
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name ?: $this->email) . '&background=6366f1&color=fff';
    }

    public function getAvatarAttribute(): ?string
    {
        return $this->attributes['avatar'] ?? $this->profile?->avatar_path ?? null;
    }

    public function getPhoneAttribute(): ?string
    {
        return $this->attributes['phone'] ?? $this->profile?->phone ?? null;
    }

    public function getBioAttribute(): ?string
    {
        return $this->profile?->bio ?? $this->attributes['bio'] ?? null;
    }

    public function getBalanceAttribute(): float
    {
        return (float) ($this->wallet?->balance ?? 0);
    }

    public function getStoreNameAttribute(): string
    {
        $shopName = trim((string) ($this->attributes['shop_name'] ?? ''));

        return $shopName !== '' ? $shopName : ($this->name ?? 'Toko');
    }

    /** Return the shop photo URL or null. */
    public function getShopPhotoUrlAttribute(): ?string
    {
        $path = $this->attributes['shop_photo'] ?? null;

        if (! $path) {
            return null;
        }

        return filter_var($path, FILTER_VALIDATE_URL)
            ? $path
            : asset('storage/' . $path);
    }

    // ─── Wallet Helpers ───────────────────────────────────────────────────────

    public function addBalance(float $amount, string $desc, ?int $orderId = null): void
    {
        $wallet       = $this->wallet()->firstOrCreate([]);
        $balanceState = $wallet->balanceState()->firstOrCreate([], [
            'balance'           => 0,
            'available_balance' => 0,
            'locked_balance'    => 0,
        ]);

        $balanceBefore = (float) $balanceState->balance;

        $balanceState->forceFill([
            'balance'           => $balanceBefore + $amount,
            'available_balance' => (float) $balanceState->available_balance + $amount,
        ])->save();

        $wallet->transactions()->create([
            'type'            => 'credit',
            'direction'       => 'credit',
            'amount'          => $amount,
            'balance_before'  => $balanceBefore,
            'balance_after'   => $balanceState->balance,
            'reference_type'  => $orderId ? Order::class : null,
            'reference_id'    => $orderId,
            'description'     => $desc,
        ]);
    }

    public function deductBalance(float $amount, string $desc, ?int $orderId = null): bool
    {
        if ($this->balance < $amount) {
            return false;
        }

        $wallet       = $this->wallet()->firstOrCreate([]);
        $balanceState = $wallet->balanceState()->firstOrCreate([], [
            'balance'           => 0,
            'available_balance' => 0,
            'locked_balance'    => 0,
        ]);

        $balanceBefore = (float) $balanceState->balance;

        $balanceState->forceFill([
            'balance'           => $balanceBefore - $amount,
            'available_balance' => (float) $balanceState->available_balance - $amount,
        ])->save();

        $wallet->transactions()->create([
            'type'            => 'debit',
            'direction'       => 'debit',
            'amount'          => $amount,
            'balance_before'  => $balanceBefore,
            'balance_after'   => $balanceState->balance,
            'reference_type'  => $orderId ? Order::class : null,
            'reference_id'    => $orderId,
            'description'     => $desc,
        ]);

        return true;
    }

    // ─── Notifications ────────────────────────────────────────────────────────

    public function sendEmailVerificationNotification(): bool
    {
        try {
            $this->notify(new VerifyEmail());

            return true;
        } catch (\Throwable $exception) {
            Log::error('Email verification notification failure', [
                'user_id'   => $this->id,
                'email'     => $this->email,
                'message'   => $exception->getMessage(),
                'exception' => get_class($exception),
            ]);

            return false;
        }
    }
}