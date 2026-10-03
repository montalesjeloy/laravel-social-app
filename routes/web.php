<?php

use App\Http\Controllers\PostController;
use App\Models\Post;
use App\Http\Controllers\HiddenPostController;
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
        ->whereDoesntHave('hiddenByUsers', function ($query) {
            $query->where('user_id', auth()->id());
        })
        ->latest()
        ->get();


        // send posts data to dashboard view
        return view('dashboard', compact('posts'));

    })->name('dashboard');

        // Hide a specific post for the authenticated user
        Route::post('/posts/{post}/hide', [HiddenPostController::class, 'hide'])
            ->name('posts.hide');

        // Unhide a specific post for the authenticated user
        Route::delete('/posts/{post}/hide', [HiddenPostController::class, 'unhide'])
            ->name('posts.unhide');

});



require __DIR__.'/settings.php';
