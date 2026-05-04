<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'username', 'email', 'password', 'role',
        'balance', 'avatar', 'phone', 'bio',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'balance' => 'decimal:2',
        ];
    }

    // Scopes
    public function scopeSeller($query) { return $query->where('role', 'seller'); }
    public function scopeActive($query) { return $query->where('is_active', true); }

    // Relations
    public function products() {
        return $this->hasMany(Product::class);
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
    public function transactions() {
        return $this->hasMany(Transaction::class);
    }
    public function withdrawals() {
        return $this->hasMany(Withdrawal::class);
    }

    // Helpers
    public function isSeller(): bool { return $this->role === 'seller'; }
    public function isAdmin(): bool  { return $this->role === 'admin'; }
    public function isBuyer(): bool  { return $this->role === 'buyer'; }

    public function getAvatarUrlAttribute(): string {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=6366f1&color=fff';
    }

    public function addBalance(float $amount, string $desc, ?int $orderId = null): void {
        $this->increment('balance', $amount);
        $this->transactions()->create([
            'type' => 'credit',
            'amount' => $amount,
            'description' => $desc,
            'order_id' => $orderId,
            'balance_after' => $this->fresh()->balance,
        ]);
    }

    public function deductBalance(float $amount, string $desc, ?int $orderId = null): bool {
        if ($this->balance < $amount) return false;
        $this->decrement('balance', $amount);
        $this->transactions()->create([
            'type' => 'debit',
            'amount' => $amount,
            'description' => $desc,
            'order_id' => $orderId,
            'balance_after' => $this->fresh()->balance,
        ]);
        return true;
    }
}