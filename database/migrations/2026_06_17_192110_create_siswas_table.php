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
        Schema::create('siswa', function (Blueprint $table) {
            $table->id('id_siswa');

            $table->foreignId('id_user')
                ->unique()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('nisn', 20)->unique();

            $table->string('jurusan', 50)->nullable();
            $table->year('angkatan')->nullable();

            $table->date('tanggal_lahir')->nullable();

            $table->enum('jenis_kelamin', ['L', 'P']);

            $table->enum('status_data', [
                'belum_lengkap',
                'proses',
                'lengkap'
            ])->default('belum_lengkap');

            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
