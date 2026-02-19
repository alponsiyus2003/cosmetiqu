<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'review_id',
        'user_id',
        'reply',
        'role',
    ];

    public function review()
    {
        return $this->belongsTo(Review::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getRoleBadgeAttribute()
    {
        return match($this->role) {
            'admin'   => 'danger',
            'penjual' => 'warning',
            default   => 'secondary',
        };
    }

    public function getRoleLabelAttribute()
    {
        return match($this->role) {
            'admin'   => 'Admin',
            'penjual' => 'Penjual',
            default   => ucfirst($this->role),
        };
    }
}
