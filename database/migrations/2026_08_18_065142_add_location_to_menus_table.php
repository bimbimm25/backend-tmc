<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            // Nilai: 'all' (Semua Cabang), 'heavenland' (Heavenland Park), 'pondok_mutiara' (Pondok Mutiara)
            $table->string('location')->default('all')->after('purchase_option');
        });
    }

    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('location');
        });
    }
};