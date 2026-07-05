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
        // Menggunakan Schema::table untuk mengubah struktur tabel yang sudah ada
        Schema::table('kemampuan', function (Blueprint $table) {
            // Menambahkan ->change() untuk memberi tahu Laravel bahwa ini adalah modifikasi kolom
            $table->text('nama_kemampuan')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kemampuan', function (Blueprint $table) {
            // Mengembalikan ke struktur semula jika di-rollback
            $table->string('nama_kemampuan', 100)->change();
        });
    }
};