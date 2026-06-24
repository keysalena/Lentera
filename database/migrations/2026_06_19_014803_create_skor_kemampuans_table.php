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
        Schema::create('skor_kemampuan', function (Blueprint $table) {
            $table->id('id_skor');

            $table->foreignId('id_eksplorasi')
                ->constrained('eksplorasi', 'id_eksplorasi')
                ->cascadeOnDelete();

            $table->foreignId('id_kemampuan')
                ->constrained('kemampuan', 'id_kemampuan')
                ->cascadeOnDelete();

            $table->tinyInteger('skor');

            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skor_kemampuans');
    }
};
