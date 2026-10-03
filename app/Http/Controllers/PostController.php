<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $posts = Post::with('user')->get();
    
        $search = $request->search;
    
        $posts = Post::where('body', 'like', "%{$search}%")
        ->get();

        return view('dashboard', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create-post');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    // From create post form
    $validated = $request->validate([
        'body' => 'nullable|string|required_without:images',

        // Multiple image upload validation
        'images' => 'nullable|array',
        'images.*' => [
            'image',
            'mimes:jpeg,png,jpg,webp',
            'max:4096'
        ],
    ]);


    // Create post record
    $post = Post::create([
        'body' => $validated['body'] ?? null,
        'user_id' => auth()->id(),
    ]);


    // Upload and save multiple images
    if ($request->hasFile('images')) {

        foreach ($request->file('images') as $image) {

            $path = $image->store('posts', 'public');

            // Save image path to post_images table
            $post->images()->create([
                'image' => $path,
            ]);
        }
    }


    // Redirect back after creating post
    return redirect()
        ->route('dashboard')
        ->with('success', 'Post created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // Only the owner can edit the post
        abort_if(auth()->id() !== $post->user_id, 403);

        // Validate the edited post
        $validated = $request->validate([
            'body' => 'nullable|string',

            // New images
            'images' => 'nullable|array',
            'images.*' => [
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:4096',
            ],

            // Existing images selected for removal
            'removed_images' => 'nullable|array',
            'removed_images.*' => 'integer',
        ]);

        // IDs of existing images the user wants to remove
        $removedImageIds = $validated['removed_images'] ?? [];

        // Only select images that actually belong to this post
        $imagesToRemove = $post->images()
            ->whereIn('id', $removedImageIds)
            ->get();

        // Check how many images will remain after editing
        $remainingImages =
            $post->images()->count()
            - $imagesToRemove->count()
            + count($request->file('images', []));

        // Post must have text OR at least one image
        if (blank($validated['body'] ?? null) && $remainingImages === 0) {
            return response()->json([
                'message' => 'A post must contain text or at least one image.',
            ], 422);
        }

        // Update the post text
        $post->update([
            'body' => $validated['body'] ?? null,
        ]);

        // Delete selected existing images
        foreach ($imagesToRemove as $image) {
            Storage::disk('public')->delete($image->image);

            $image->delete();
        }

        // Save newly added images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('posts', 'public');

                $post->images()->create([
                    'image' => $path,
                ]);
            }
        }

        return response()->json([
            'message' => 'Post updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        
        // Allow only the owner of the post to delete it
        abort_if(auth()->id() !== $post->user_id, 403);

     // Delete all uploaded image files from storage
    foreach ($post->images as $image) {

        Storage::disk('public')->delete($image->image);

    }
        // Delete the post
        // (cascadeOnDelete() will automatically delete all related post_images)
        $post->delete();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Post deleted successfully!');
    }
}
