<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('bph_ukm_ukm', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ukm_id'); // ID UKM
            $table->unsignedBigInteger('bph_ukm_id'); // ID user dengan role BPH UKM
            $table->timestamps();

            // Foreign keys
            $table->foreign('ukm_id')->references('id')->on('ukms')->onDelete('cascade');
            $table->foreign('bph_ukm_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bph_ukm_ukm');
    }
};
