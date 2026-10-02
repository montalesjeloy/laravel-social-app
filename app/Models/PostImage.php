<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostImage extends Model
{
    //allow fields to save
    protected $fillable = [
        'post_id',
        'image',
    ];

    //image belongs to a post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
