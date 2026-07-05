<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'id_role' => 1,
            // 'id_sekolah' => 1,
            'nama' => 'Administrator',
            'email' => 'admin@lentera.id',
            'password' => Hash::make('password'),
        ]);

        // Guru (7 akun)
        for ($i = 1; $i <= 7; $i++) {
            User::create([
                'id_role' => 2,
                'id_sekolah' => $i,
                'nama' => "Guru $i",
                'email' => "guru$i@lentera.id",
                'password' => Hash::make('12345678'),
            ]);
        }

        // Siswa (10 akun)
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'id_role' => 3,
                'id_sekolah' => $i,
                'nama' => "Siswa $i",
                'email' => "siswa$i@lentera.id",
                'password' => Hash::make('12345678'),
            ]);
        }
    }
}