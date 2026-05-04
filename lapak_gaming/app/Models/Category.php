<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model {
    protected $fillable = ['parent_id', 'name', 'slug', 'icon', 'image', 'sort_order', 'is_active'];

    protected function casts(): array {
        return ['is_active' => 'boolean'];
    }

    protected static function boot() {
        parent::boot();
        static::creating(fn($cat) => $cat->slug = $cat->slug ?? Str::slug($cat->name));
    }

    public function parent()    { return $this->belongsTo(Category::class, 'parent_id'); }
    public function children()  { return $this->hasMany(Category::class, 'parent_id'); }
    public function products()  { return $this->hasMany(Product::class); }

    public function scopeActive($q)   { return $q->where('is_active', true); }
    public function scopeParent($q)   { return $q->whereNull('parent_id'); }
    public function scopeOrdered($q)  { return $q->orderBy('sort_order'); }

    public function getImageUrlAttribute(): string {
        return $this->image ? asset('storage/' . $this->image) : asset('images/default-category.png');
    }
}