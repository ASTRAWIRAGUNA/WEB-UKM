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
        Schema::create('activities', function (Blueprint $table) {
            $table->id('activities_id');
            $table->foreignId('ukm_id')->constrained('ukms', 'ukm_id')->onDelete('cascade');
            $table->string('name_activity');
            $table->date('date');
            $table->string('proof_photo')->nullable(); // Bukti foto kegiatan
            $table->string('status_activity');
            $table->string('message')->nullable();
            $table->string('qr_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
