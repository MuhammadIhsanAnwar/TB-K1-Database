<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Buyer extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'buyers';

    protected $fillable = [
        'user_id',
    ];

    public function scopeActive($query)
    {
        return $query->whereHas('user', fn ($user) => $user->where('status', 'active'));
    }

    public function scopeVerified($query)
    {
        return $query->whereHas('user', fn ($user) => $user->whereNotNull('email_verified_at'));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'buyer_id');
    }

    public function cart()
    {
        return $this->hasMany(Cart::class, 'buyer_id');
    }

    public function addresses()
    {
        return $this->hasMany(UserAddress::class, 'buyer_id');
    }

    public function notifications()
    {
        return $this->morphMany('Illuminate\Notifications\DatabaseNotification', 'notifiable')->latest();
    }

    public function getNameAttribute(): ?string
    {
        return $this->user?->name;
    }

    public function getUsernameAttribute(): ?string
    {
        return $this->user?->username;
    }

    public function getEmailAttribute(): ?string
    {
        return $this->user?->email;
    }

    public function getPhoneAttribute(): ?string
    {
        return $this->user?->phone;
    }

    public function getAvatarAttribute(): ?string
    {
        return $this->user?->avatar;
    }

    public function getStatusAttribute(): ?string
    {
        return $this->user?->status;
    }

    public function getSuspendedAtAttribute(): mixed
    {
        return $this->user?->suspended_at;
    }
}
