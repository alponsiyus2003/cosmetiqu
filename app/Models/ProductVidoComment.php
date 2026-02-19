<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVideoComment extends Model
{
    protected $fillable = ['product_video_id', 'user_id', 'comment'];

    public function video()
    {
        return $this->belongsTo(ProductVideo::class, 'product_video_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
