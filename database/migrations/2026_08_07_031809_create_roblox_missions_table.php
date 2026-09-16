<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roblox_missions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('reward_title');
            $table->string('reward_code')->nullable();
            $table->string('roblox_map_link')->nullable();
            $table->string('trailer_video_url')->nullable();
            $table->string('image')->nullable(); // Screenshot Map Roblox
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roblox_missions');
    }
};