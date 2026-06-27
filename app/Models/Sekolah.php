<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Sekolah extends Model
{
    use SoftDeletes;

    protected $table = 'sekolah';

    protected $primaryKey = 'id_sekolah';

    protected $fillable = [
        'nama_sekolah',
        'alamat',
        'kode_lisensi',
        'kode_lisensi_siswa',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'id_sekolah', 'id_sekolah');
    }
    protected static function booted()
    {
        static::creating(function ($sekolah) {
            // Generate otomatis saat pertama kali dibuat jika kosong
            if (empty($sekolah->kode_lisensi)) {
                $sekolah->kode_lisensi = 'G-' . strtoupper(Str::random(3)) . '-' . strtoupper(Str::random(3));
            }
            if (empty($sekolah->kode_lisensi_siswa)) {
                $sekolah->kode_lisensi_siswa = 'S-' . strtoupper(Str::random(3)) . '-' . strtoupper(Str::random(3));
            }
        });
    }

    // Fungsi helper untuk mereset/mengganti lisensi secara paksa
    public function regenerateLicenses()
    {
        $this->update([
            'kode_lisensi' => 'G-' . strtoupper(Str::random(3)) . '-' . strtoupper(Str::random(3)),
            'kode_lisensi_siswa' => 'S-' . strtoupper(Str::random(3)) . '-' . strtoupper(Str::random(3)),
        ]);
    }
}