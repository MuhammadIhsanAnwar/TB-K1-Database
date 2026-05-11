<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPolicyConsent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'policy_type',
        'version',
        'agreed_at',
        'ip_address',
        'user_agent',
        'consent_status',
    ];

    protected $casts = [
        'agreed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeTermsOfService($query)
    {
        return $query->where('policy_type', 'terms_of_service');
    }

    public function scopePrivacyPolicy($query)
    {
        return $query->where('policy_type', 'privacy_policy');
    }

    public function scopeAgreed($query)
    {
        return $query->where('consent_status', 'agreed');
    }

    public function scopeDeclined($query)
    {
        return $query->where('consent_status', 'declined');
    }
}
