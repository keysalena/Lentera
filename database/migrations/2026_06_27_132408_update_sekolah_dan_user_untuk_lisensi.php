<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // 1. Tambah Kode Lisensi Siswa di tabel Sekolah
        Schema::table('sekolah', function (Blueprint $table) {
            $table->string('kode_lisensi_siswa')->unique()->nullable()->after('kode_lisensi');
        });

        // 2. Ubah id_sekolah jadi nullable di tabel Users (untuk siswa mandiri)
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('id_sekolah')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('sekolah', function (Blueprint $table) {
            $table->dropColumn('kode_lisensi_siswa');
        });
        // Abaikan rollback untuk nullable id_sekolah agar data tidak crash
    }
};