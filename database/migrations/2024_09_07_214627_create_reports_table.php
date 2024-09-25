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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ukm_id'); // ID UKM yang berkaitan dengan laporan
            $table->unsignedBigInteger('bph_ukm_id'); // ID BPH UKM yang membuat laporan
            $table->string('title'); // Judul laporan
            $table->text('description'); // Deskripsi laporan
            $table->string('proof_photo')->nullable(); // Opsional: Bukti foto kegiatan
            $table->string('status')->default('pending'); // Status laporan: pending, approved, rejected
            $table->timestamps();

            // Relasi foreign key
            $table->foreign('ukm_id')->references('id')->on('ukms')->onDelete('cascade');
            $table->foreign('bph_ukm_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
