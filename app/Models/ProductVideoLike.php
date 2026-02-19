<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVideoLike extends Model
{
    protected $fillable = ['product_video_id', 'user_id'];

    public function video()
    {
        return $this->belongsTo(ProductVideo::class, 'product_video_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
