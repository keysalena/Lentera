<?php

namespace Database\Seeders;

use App\Models\MataPelajaran;
use Illuminate\Database\Seeder;

class MataPelajaranSeeder extends Seeder
{
    public function run(): void
    {
        $mapel = [
            'Agama',
            'Pancasila',
            'Bahasa Indonesia',
            'Matematika',
            'IPA',
            'IPS',
            'Bahasa Inggris',
            'PJOK',
            'Informatika',
            'Seni Budaya',
            'Logika',
            'Kreativitas',
            'Komnikasi',
            'Kepemimpinan',
            'Problem Solving',
            'Teamwork',
            'Literasi',
            'Numerasi',
        ];

        foreach ($mapel as $item) {
            MataPelajaran::create([
                'nama_mapel' => $item
            ]);
        }
    }
}