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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('articleTitle');
            $table->text('articleThumbnail');
            $table->text('articleThumbnailTitle');
            $table->text('articleContent');
            $table->foreignId('user_id')->constrained();
            $table->string('articleSlug')->unique();
            $table->foreignId('category_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
