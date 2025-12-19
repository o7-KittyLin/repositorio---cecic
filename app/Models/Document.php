<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'file_path',
        'preview_path',
        'price',
        'is_free',
        'views_count',
        'likes_count',
        'files_count',
        'is_published',
        'tags',
        'is_active'
    ];

    protected $casts = [
        'tags' => 'array',
        'is_free' => 'boolean',
        'is_published' => 'boolean'
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function purchaseRequests()
    {
        return $this->hasMany(PurchaseRequest::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }


    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeFree($query)
    {
        return $query->where('is_free', true);
    }

    public function scopePaid($query)
    {
        return $query->where('is_free', false);
    }

    public function scopePopular($query)
    {
        return $query->orderBy('views_count', 'desc');
    }

    public function scopeMostLiked($query)
    {
        return $query->orderBy('likes_count', 'desc');
    }

    // Métodos
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function isPurchasedBy($user)
    {
        if (!$user) return false;

        return $this->purchases()
            ->where('user_id', $user->id)
            ->where('payment_status', 'completed')
            ->exists();
    }

    public function hasPendingRequest($user): bool
    {
        if (!$user) {
            return false;
        }

        return $this->purchaseRequests()
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();
    }

    public function isLikedBy($user)
    {
        if (!$user) return false;

        return $this->likes()
            ->where('user_id', $user->id)
            ->exists();
    }

    public function isFavoritedBy($user)
    {
        if (!$user) return false;

        return $this->favorites()
            ->where('user_id', $user->id)
            ->exists();
    }

}
