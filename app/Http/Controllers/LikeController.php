<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class LikeController extends Controller
{
    /**
     * Like a post.
     */
    public function like(Post $post): JsonResponse
    {
        // Create the like only if it does not already exist
        Like::firstOrCreate([
            'user_id' => auth()->id(),
            'post_id' => $post->id,
        ]);

        return response()->json([
            'liked' => true,
            'count' => $post->likes()->count(),
        ]);
    }

    /**
     * Unlike a post.
     */
    public function unlike(Post $post): JsonResponse
    {
        // Remove the authenticated user's like
        Like::where('user_id', auth()->id())
            ->where('post_id', $post->id)
            ->delete();

        return response()->json([
            'liked' => false,
            'count' => $post->likes()->count(),
        ]);
    }
}
