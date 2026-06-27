<?php

namespace Database\Seeders;

use App\Models\Sekolah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SekolahSeeder extends Seeder
{
    public function run(): void
    {
        Sekolah::create([
            'nama_sekolah' => 'SMAN 5 Malang',
            'alamat' => 'Jl. Ikan Piranha Atas No.185, Malang',
            'kode_lisensi' => sprintf(
                'G-%s-%s',
                strtoupper(Str::random(3)),
                strtoupper(Str::random(3))
            ),
            'kode_lisensi_siswa' => sprintf(
                'S-%s-%s',
                strtoupper(Str::random(3)),
                strtoupper(Str::random(3))
            ),
        ]);
    }
}
