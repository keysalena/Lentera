<?php

namespace Database\Seeders;

use App\Models\Kemampuan;
use Illuminate\Database\Seeder;

class KemampuanSeeder extends Seeder
{
    public function run(): void
    {
        $kemampuan = [
            'Komunikasi',
            'Kepemimpinan',
            'Kreativitas',
            'Logika',
            'Teknologi',
            'Riset',
            'Seni',
            'Olahraga',
            'Organisasi',
            'Kewirausahaan',
            'Kerja Tim',
            'Problem Solving',
        ];

        foreach ($kemampuan as $item) {
            Kemampuan::create([
                'nama_kemampuan' => $item
            ]);
        }
    }
}