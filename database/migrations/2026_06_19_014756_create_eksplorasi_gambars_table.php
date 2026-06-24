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
        Schema::create('eksplorasi_gambar', function (Blueprint $table) {
            $table->id('id_gambar');

            $table->foreignId('id_eksplorasi')
                ->constrained('eksplorasi', 'id_eksplorasi')
                ->cascadeOnDelete();

            $table->string('gambar');
            $table->longText('hasil_ocr')->nullable();

            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eksplorasi_gambars');
    }
};
