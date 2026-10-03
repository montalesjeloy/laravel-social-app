<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HiddenPost extends Model
{
    protected $fillable = [
        'user_id',
        'post_id',
    ];

    /**
     * Get the user who hid the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the post that was hidden.
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
