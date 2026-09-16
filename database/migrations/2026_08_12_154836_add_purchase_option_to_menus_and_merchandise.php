<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('menus')) {
            Schema::table('menus', function (Blueprint $table) {
                $table->string('purchase_option')->default('In Store')->after('price');
            });
        }

        if (Schema::hasTable('merchandises')) {
            Schema::table('merchandises', function (Blueprint $table) {
                $table->string('purchase_option')->default('In Store')->after('price');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('menus')) {
            Schema::table('menus', function (Blueprint $table) {
                $table->dropColumn('purchase_option');
            });
        }

        if (Schema::hasTable('merchandises')) {
            Schema::table('merchandises', function (Blueprint $table) {
                $table->dropColumn('purchase_option');
            });
        }
    }
};