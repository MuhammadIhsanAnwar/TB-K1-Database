<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\UserAddress;
use App\Models\UserProfile;

class User extends Authenticatable implements MustVerifyEmail {
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'username', 'email', 'password', 'role',
        'status', 'seller_level_id', 'suspended_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Scopes
    public function scopeSeller($query) { return $query->where('role', 'seller'); }
    public function scopeActive($query) { return $query->where('is_active', true); }

    // Relations
    public function products() {
        return $this->hasMany(Product::class, 'seller_id');
    }
    public function orders() { // sebagai buyer
        return $this->hasMany(Order::class);
    }
    public function soldItems() { // sebagai seller
        return $this->hasMany(OrderItem::class, 'seller_id');
    }
    public function reviews() {
        return $this->hasMany(Review::class);
    }
    public function cart() {
        return $this->hasMany(Cart::class);
    }
    public function wallet(): HasOne {
        return $this->hasOne(Wallet::class);
    }

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    public function address(): HasOne
    {
        return $this->hasOne(UserAddress::class);
    }

    public function transactions() {
        return $this->hasMany('App\\Models\\Transaction');
    }

    // Helpers
    public function isSeller(): bool { return $this->role === 'seller'; }
    public function isAdmin(): bool  { return $this->role === 'admin'; }
    public function isBuyer(): bool  { return $this->role === 'buyer'; }

    public function getAvatarUrlAttribute(): string {
        $avatarPath = $this->profile?->avatar_path ?? $this->attributes['avatar'] ?? null;

        return $avatarPath
            ? asset('storage/' . $avatarPath)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name ?: $this->username ?: $this->email) . '&background=6366f1&color=fff';
    }

    public function getAvatarAttribute(): ?string
    {
        return $this->profile?->avatar_path ?? $this->attributes['avatar'] ?? null;
    }

    public function getPhoneAttribute(): ?string
    {
        return $this->profile?->phone ?? $this->attributes['phone'] ?? null;
    }

    public function getBioAttribute(): ?string
    {
        return $this->profile?->bio ?? $this->attributes['bio'] ?? null;
    }

    public function getBalanceAttribute(): float
    {
        return (float) ($this->wallet?->balance ?? 0);
    }

    public function addBalance(float $amount, string $desc, ?int $orderId = null): void {
        $wallet = $this->wallet()->firstOrCreate([]);
        $balanceState = $wallet->balanceState()->firstOrCreate([], [
            'balance' => 0,
            'available_balance' => 0,
            'locked_balance' => 0,
        ]);

        $balanceState->forceFill([
            'balance' => (float) $balanceState->balance + $amount,
            'available_balance' => (float) $balanceState->available_balance + $amount,
        ])->save();

        $this->transactions()->create([
            'type' => 'credit',
            'amount' => $amount,
            'description' => $desc,
            'order_id' => $orderId,
            'balance_after' => $balanceState->balance,
        ]);
    }

    public function deductBalance(float $amount, string $desc, ?int $orderId = null): bool {
        if ($this->balance < $amount) return false;

        $wallet = $this->wallet()->firstOrCreate([]);
        $balanceState = $wallet->balanceState()->firstOrCreate([], [
            'balance' => 0,
            'available_balance' => 0,
            'locked_balance' => 0,
        ]);

        $balanceState->forceFill([
            'balance' => (float) $balanceState->balance - $amount,
            'available_balance' => (float) $balanceState->available_balance - $amount,
        ])->save();

        $this->transactions()->create([
            'type' => 'debit',
            'amount' => $amount,
            'description' => $desc,
            'order_id' => $orderId,
            'balance_after' => $balanceState->balance,
        ]);
        return true;
    }
}