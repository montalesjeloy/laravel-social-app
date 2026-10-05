<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    protected $fillable = [
        'body',
        'user_id',
    ];

    // Post belongs to 1 user
    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // 1 post can have multiple images
    /**
     * @return HasMany<PostImage, $this>
     */
    public function images(): HasMany
    {
        return $this->hasMany(PostImage::class);
    }

    // 1 post can be hidden by multiple users
    /**
     * @return HasMany<HiddenPost, $this>
     */
    public function hiddenByUsers(): HasMany
    {
        return $this->hasMany(HiddenPost::class);
    }
}
