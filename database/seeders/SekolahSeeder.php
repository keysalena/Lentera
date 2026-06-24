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
                'LENTERA-%s-%s',
                strtoupper(Str::random(4)),
                strtoupper(Str::random(4))
            ),
        ]);
    }
}
