<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'video_path',
        'thumbnail_path',
        'title',
        'description',
        'duration',
        'views',
        'likes',
        'is_active',
    ];

    protected $casts = [
        'duration' => 'integer',
        'views' => 'integer',
        'likes' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function videoLikes()
    {
        return $this->hasMany(ProductVideoLike::class);
    }

    public function comments()
    {
        return $this->hasMany(ProductVideoComment::class)->latest();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessors
    public function getVideoUrlAttribute()
    {
        return asset('storage/' . $this->video_path);
    }

    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail_path) {
            return asset('storage/' . $this->thumbnail_path);
        }
        return null;
    }

    public function getFormattedViewsAttribute()
    {
        if ($this->views >= 1000000) {
            return round($this->views / 1000000, 1) . 'M';
        } elseif ($this->views >= 1000) {
            return round($this->views / 1000, 1) . 'K';
        }
        return $this->views;
    }

    public function getFormattedLikesAttribute()
    {
        if ($this->likes >= 1000000) {
            return round($this->likes / 1000000, 1) . 'M';
        } elseif ($this->likes >= 1000) {
            return round($this->likes / 1000, 1) . 'K';
        }
        return $this->likes;
    }

    public function isLikedBy($userId)
    {
        return $this->videoLikes()->where('user_id', $userId)->exists();
    }

    // Methods
    public function incrementViews()
    {
        $this->increment('views');
    }

    public function toggleLike($userId)
    {
        $like = $this->videoLikes()->where('user_id', $userId)->first();

        if ($like) {
            $like->delete();
            $this->decrement('likes');
            return false;
        } else {
            $this->videoLikes()->create(['user_id' => $userId]);
            $this->increment('likes');
            return true;
        }
    }
}
