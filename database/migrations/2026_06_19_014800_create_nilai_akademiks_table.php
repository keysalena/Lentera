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
        Schema::create('nilai_akademik', function (Blueprint $table) {
            $table->id('id_nilai');

            $table->foreignId('id_eksplorasi')
                ->constrained('eksplorasi', 'id_eksplorasi')
                ->cascadeOnDelete();

            $table->foreignId('id_mapel')
                ->constrained('mata_pelajaran', 'id_mapel')
                ->cascadeOnDelete();

            $table->decimal('nilai', 5, 2);

            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_akademiks');
    }
};
