<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            SekolahSeeder::class,
            UserSeeder::class,
            MataPelajaranSeeder::class,
            KemampuanSeeder::class,
        ]);
    }
}
