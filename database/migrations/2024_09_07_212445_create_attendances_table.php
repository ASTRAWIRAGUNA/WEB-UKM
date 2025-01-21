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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id('attendances_id');
            // $table->foreignId('activities_id') // Foreign key untuk activities
            //     ->constrained('activities') // Mengacu ke tabel 'activities' dengan kolom 'id'
            //     ->onDelete('set null'); // Menghapus relasi activities_id jika activities dihapus
            $table->unsignedBigInteger('activities_id')->nullable(); // Explicit unsignedBigInteger
            $table->unsignedBigInteger('user_id')->nullable(); // Explicit unsignedBigInteger
            // $table->foreign('user_id')
            //     ->references('user_id')
            //     ->on('users')
            //     ->onDelete('set null'); // Menghapus relasi user_id jika user dihapus
            $table->boolean('is_present')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
