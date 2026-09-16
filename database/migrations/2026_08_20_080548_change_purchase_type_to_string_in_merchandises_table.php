<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('merchandises', function (Blueprint $table) {
            // Ubah tipe kolom purchase_type menjadi string biasa
            $table->string('purchase_type', 100)->default('In Store')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('merchandises', function (Blueprint $table) {
            $table->string('purchase_type', 100)->nullable()->change();
        });
    }
};