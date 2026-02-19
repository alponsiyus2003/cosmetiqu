<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'rating',
        'comment',
        'is_approved',
        'is_verified_purchase',
        'helpful_count',
    ];

    protected $casts = [
        'rating'               => 'integer',
        'is_approved'          => 'boolean',
        'is_verified_purchase' => 'boolean',
        'helpful_count'        => 'integer',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function media()
    {
        return $this->hasMany(ReviewMedia::class)->orderBy('order');
    }

    public function images()
    {
        return $this->hasMany(ReviewMedia::class)->where('type', 'image')->orderBy('order');
    }

    public function videos()
    {
        return $this->hasMany(ReviewMedia::class)->where('type', 'video')->orderBy('order');
    }

    public function replies()
    {
        return $this->hasMany(ReviewReply::class)->with('user')->latest();
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    // Accessors
    public function getStarsHtmlAttribute()
    {
        $html = '';
        for ($i = 1; $i <= 5; $i++) {
            $html .= $i <= $this->rating
                ? '<i class="fas fa-star text-warning"></i>'
                : '<i class="far fa-star text-warning"></i>';
        }
        return $html;
    }

    public function getRatingLabelAttribute()
    {
        return match($this->rating) {
            1 => 'Sangat Buruk',
            2 => 'Buruk',
            3 => 'Cukup',
            4 => 'Bagus',
            5 => 'Sangat Bagus',
            default => '',
        };
    }

    public function getHasMediaAttribute()
    {
        return $this->media()->exists();
    }
}
