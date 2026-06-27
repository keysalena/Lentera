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
        Schema::create('konsultasi', function (Blueprint $table) {
            $table->id('id_konsultasi'); // Primary key
            
            // Relasi ke tabel lain
            $table->unsignedBigInteger('id_siswa');
            $table->unsignedBigInteger('id_eksplorasi'); // Merujuk pada hasil tes AI mana yang didiskusikan
            $table->unsignedBigInteger('id_guru')->nullable(); // Bisa null karena saat awal diajukan, belum ada guru yang menangani
            
            // Data pengajuan dari siswa
            $table->string('topik'); // Contoh: "Hasil AI tidak sesuai minat", "Bingung memilih kampus"
            $table->text('alasan_siswa')->nullable(); // Penjelasan tambahan dari siswa

            // Sistem Triage (Prioritas & Status)
            $table->enum('tingkat_prioritas', ['Tinggi', 'Menengah', 'Rendah'])->default('Rendah');
            $table->enum('status', ['Menunggu', 'Dijadwalkan', 'Selesai', 'Dibatalkan'])->default('Menunggu');

            // Data tindak lanjut dari Guru BK
            $table->dateTime('jadwal_konsultasi')->nullable();
            $table->text('catatan_guru')->nullable(); // Laporan setelah konseling tatap muka selesai

            $table->timestamps();

            // Definisi Foreign Key (Buka komentar di bawah ini jika Anda ingin memberlakukan strict relation di MySQL)
            $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->onDelete('cascade');
            $table->foreign('id_eksplorasi')->references('id_eksplorasi')->on('eksplorasi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konsultasi');
    }
};
