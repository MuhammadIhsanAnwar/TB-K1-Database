<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentLike extends Model
{
    use HasFactory;

    protected $fillable = ['product_comment_id', 'user_id'];

    public $timestamps = false;

    // Relations
    public function comment(): BelongsTo
    {
        return $this->belongsTo(ProductComment::class, 'product_comment_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
