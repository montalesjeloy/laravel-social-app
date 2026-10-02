<?php

namespace App\Http\Controllers;

use App\Models\Post;

class DashboardController extends Controller
{
     public function index()
    {
    // Get all posts with user and images relationship
        $posts = Post::with([
            'user',
            'images'
        ])
        ->latest()
        ->get();


        // Pass posts data to dashboard view
        return view('dashboard', compact('posts'));
    }
}
