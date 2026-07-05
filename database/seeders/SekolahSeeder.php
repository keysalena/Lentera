<?php

namespace Database\Seeders;

use App\Models\Sekolah;
use Illuminate\Database\Seeder;

class SekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sekolah = [
            [
                'nama_sekolah' => 'SMA Negeri 1 Malang',
                'alamat' => 'Jl. Tugu No. 1, Malang',
            ],
            [
                'nama_sekolah' => 'SMA Negeri 2 Malang',
                'alamat' => 'Jl. Laksamana Martadinata No. 84, Malang',
            ],
            [
                'nama_sekolah' => 'SMA Negeri 3 Malang',
                'alamat' => 'Jl. Sultan Agung No. 7, Malang',
            ],
            [
                'nama_sekolah' => 'SMA Negeri 4 Malang',
                'alamat' => 'Jl. Tugu Utara No. 1, Malang',
            ],
            [
                'nama_sekolah' => 'SMA Negeri 5 Malang',
                'alamat' => 'Jl. Tanimbar No. 24, Malang',
            ],
            [
                'nama_sekolah' => 'SMA Negeri 6 Malang',
                'alamat' => 'Jl. Cipayung No. 15, Malang',
            ],
            [
                'nama_sekolah' => 'SMA Negeri 7 Malang',
                'alamat' => 'Jl. Cengger Ayam I No. 14, Malang',
            ],
            [
                'nama_sekolah' => 'SMA Negeri 8 Malang',
                'alamat' => 'Jl. Veteran No. 37, Malang',
            ],
            [
                'nama_sekolah' => 'SMA Negeri 9 Malang',
                'alamat' => 'Jl. Puncak Borobudur No. 1, Malang',
            ],
            [
                'nama_sekolah' => 'SMA Negeri 10 Malang',
                'alamat' => 'Jl. Danau Grati No. 1, Malang',
            ],
        ];

        foreach ($sekolah as $item) {
            Sekolah::create($item);
        }
    }
}