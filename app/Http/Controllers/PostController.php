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
        'body' => 'required_without:images|string',

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
        $validated = $request->validate([
            'body' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:4096',

        ]);

        if ($request->hasFile('image')) {
        
            //delete old image
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }

            //save new image
            $validated['image'] = $request->file('image')
            ->store('posts', 'public');

        } else {
            //keep the old image
            $validated['image'] = $post->image;
        }

        // $post = Post::findOrFail($id);
        $post->update($validated);

        return redirect()->route('dashboard')
        ->with('success', 'Post updated successfully!');
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
