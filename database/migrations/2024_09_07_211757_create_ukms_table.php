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
            $table->string('profile_photo')->nullable(); // Untuk profile UKM
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
