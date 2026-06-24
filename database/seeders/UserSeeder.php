<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'id_role' => 1, // admin
            'id_sekolah' => 1,

            'nama' => 'Administrator',
            'email' => 'admin@lentera.id',
            'password' => Hash::make('password'),
        ]);
    }
}