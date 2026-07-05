<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kemampuan', function (Blueprint $table) {
            // Menambahkan kolom kode_item setelah kolom id_kemampuan
            $table->string('kode_item', 10)->unique()->after('id_kemampuan');
        });
    }

    public function down(): void
    {
        Schema::table('kemampuan', function (Blueprint $table) {
            $table->dropColumn('kode_item');
        });
    }
};