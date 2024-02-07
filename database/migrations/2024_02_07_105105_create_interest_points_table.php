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
        Schema::create('interest_points', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('pointName');
            $table->string('pointTitle');
            $table->string('pointSlug')->unique();
            $table->text('pointDescription');
            $table->text('pointThumbnail');
            $table->text('pointThumbnailTitle');
            $table->foreignId('user_id')->constrained();
            $table->string('pointtips')->nullable();
            $table->string('pointAdress');
            $table->string('pointSpeciality');
            $table->text('pointContent')->nullable();
            $table->foreignId('pointPicture_id')->constrained();
            $table->foreignId('pointCategories_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interest_points');
    }
};
