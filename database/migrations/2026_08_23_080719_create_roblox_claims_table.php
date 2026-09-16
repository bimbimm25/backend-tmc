<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roblox_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('roblox_mission_id')->constrained('roblox_missions')->onDelete('cascade');
            $table->string('roblox_username');
            $table->string('whatsapp_number');
            $table->string('proof_image'); // Screenshot bukti in-game
            $table->string('claim_code')->unique(); // Kode unik untuk ditunjukkan ke kasir cafe (misal: TMC-CHEF-7842)
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roblox_claims');
    }
};