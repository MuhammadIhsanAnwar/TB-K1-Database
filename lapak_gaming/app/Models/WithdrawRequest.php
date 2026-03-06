<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WithdrawRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'seller_id',
        'amount',
        'status',
        'bank_account_name',
        'bank_name',
        'account_number',
        'approved_at',
        'completed_at',
        'admin_note',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
