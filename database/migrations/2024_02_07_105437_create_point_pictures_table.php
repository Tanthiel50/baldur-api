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
        Schema::create('point_pictures', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('pictureTitle');
            $table->text('picturePath');
            $table->foreignId('point_id')->constrained();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_pictures');
    }
};
