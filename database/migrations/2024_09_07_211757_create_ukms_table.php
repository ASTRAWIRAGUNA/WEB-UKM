<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('ukms', function (Blueprint $table) {
            $table->id('ukm_id');
            $table->string('name_ukm');
            $table->text('description')->nullable();
            $table->string('profile_photo_ukm')->nullable(); // Untuk profile UKM
            $table->foreignId('bph_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->enum('registration_status', ['active', 'deactivated'])->default('deactivated');
            $table->integer('min_activity')->default(0)->nullable();
            $table->integer('cash')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ukms');
    }
};
