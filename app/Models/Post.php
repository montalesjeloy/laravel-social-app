<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'body',
        'user_id',
    ];

    // Post belongs to 1 user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 1 post can have multiple images
    public function images()
    {
        return $this->hasMany(PostImage::class);
    }

    // 1 post can be hidden by multiple users
    public function hiddenByUsers()
    {
        return $this->hasMany(HiddenPost::class);
    }
}
