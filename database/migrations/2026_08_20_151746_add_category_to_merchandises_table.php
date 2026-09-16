<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('merchandises', function (Blueprint $table) {
            // Menambahkan kolom category setelah nama produk
            $table->string('category')->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('merchandises', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};