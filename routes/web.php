<?php

use App\Http\Controllers\PostController;
use App\Models\Post;
// use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Route;

// get route example
Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::resource('posts', PostController::class);

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('dashboard', function () {

        // get latest posts with user and images
        $posts = Post::with([
            'user',
            'images'
        ])
        ->latest()
        ->get();


        // send posts data to dashboard view
        return view('dashboard', compact('posts'));

    })->name('dashboard');

});

// Route::get('dashboard', function () {

//     // get posts
//     $posts = Post::all();

//     return view('dashboard', compact('posts'));

// })->name('dashboard');

require __DIR__.'/settings.php';
