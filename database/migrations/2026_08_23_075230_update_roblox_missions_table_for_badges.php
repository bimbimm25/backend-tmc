<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roblox_missions', function (Blueprint $table) {
            if (!Schema::hasColumn('roblox_missions', 'category')) {
                $table->string('category')->default('Chef Career')->after('id'); // Chef Career, Cashier Career, Obby Challenge
            }
            if (!Schema::hasColumn('roblox_missions', 'badge_name')) {
                $table->string('badge_name')->nullable()->after('title');
            }
            if (!Schema::hasColumn('roblox_missions', 'requirement')) {
                $table->string('requirement')->nullable()->after('description'); // Contoh: Masak 25 menu
            }
            if (!Schema::hasColumn('roblox_missions', 'reward_item')) {
                $table->string('reward_item')->nullable()->after('reward_title'); // Contoh: Free Sticker / Diskon 20%
            }
        });
    }

    public function down(): void
    {
        Schema::table('roblox_missions', function (Blueprint $table) {
            $table->dropColumn(['category', 'badge_name', 'requirement', 'reward_item']);
        });
    }
};