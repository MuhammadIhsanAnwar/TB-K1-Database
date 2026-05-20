<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'currency',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function balanceState(): HasOne
    {
        return $this->hasOne(WalletBalance::class);
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function getBalanceAttribute(): float
    {
        return (float) ($this->balanceState?->balance ?? 0);
    }

    public function getAvailableBalanceAttribute(): float
    {
        return (float) ($this->balanceState?->available_balance ?? 0);
    }

    public function getLockedBalanceAttribute(): float
    {
        return (float) ($this->balanceState?->locked_balance ?? 0);
    }
}