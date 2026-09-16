<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // Pastikan tabel posts memiliki foreignId atau category_id
        if (Schema::hasTable('posts') && !Schema::hasColumn('posts', 'category_id')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->foreignId('category_id')->nullable()->after('type')->constrained('blog_categories')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_categories');
    }
};