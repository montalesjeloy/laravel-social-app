<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hidden_posts', function (Blueprint $table) {
            $table->id();

            // User who hid the post
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Post that was hidden
            $table->foreignId('post_id')
                ->constrained()
                ->cascadeOnDelete();

            // Prevent the same user from hiding the same post multiple times
            $table->unique(['user_id', 'post_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hidden_posts');
    }
};
