<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;

class HiddenPostController extends Controller
{
    /**
     * Hide a post for the authenticated user.
     */
    public function hide(Post $post): JsonResponse
    {
        
        // Prevent the authenticated user from hiding their own post
        abort_if(auth()->id() === $post->user_id, 403);

        // Hide the post if it has not already been hidden
        auth()->user()->hiddenPosts()->firstOrCreate([
            'post_id' => $post->id,
        ]);

        // Return to the previous page
        return response()->json(['message' => 'Post hidden successfully.']);
    }

    /**
     * Unhide a post for the authenticated user.
     */
    public function unhide(Post $post): JsonResponse
    {
        // Find and remove the hidden post record
        auth()->user()
            ->hiddenPosts()
            ->where('post_id', $post->id)
            ->delete();

        // Send a JSON response back to the frontend
        return response()->json(['message' => 'Post restored successfully.']);
    }
}
