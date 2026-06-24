<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_role')
                ->constrained('roles', 'id_role')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('id_sekolah')
                ->nullable()
                ->constrained('sekolah', 'id_sekolah')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->string('nama', 150);
            $table->string('email')->unique();
            $table->string('password');

            $table->enum('status', [
                'aktif',
                'nonaktif'
            ])->default('aktif');

            $table->rememberToken();

            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};