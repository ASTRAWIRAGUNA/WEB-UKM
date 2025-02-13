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
        Schema::create('kas', function (Blueprint $table) {
            $table->id('kas_id');
            $table->foreignId('ukm_id')
                ->constrained('ukms', 'ukm_id')
                ->onDelete('cascade');

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users', 'user_id')
                ->onDelete('set null');

            $table->foreignId('activities_id')
                ->nullable()
                ->constrained('activities', 'activities_id')
                ->onDelete('cascade'); // Pastikan kas ikut terhapus jika activity dihapus

            $table->integer('amount')->default(0);
            $table->boolean('is_payment')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kas');
    }
};
