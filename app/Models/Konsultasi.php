<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konsultasi extends Model
{
    use HasFactory;

    // Arahkan ke nama tabel yang benar
    protected $table = 'konsultasi';
    protected $primaryKey = 'id_konsultasi';

    // Izinkan pengisian data massal untuk kolom-kolom ini
    protected $fillable = [
        'id_siswa',
        'id_eksplorasi',
        'id_guru',
        'topik',
        'alasan_siswa',
        'tingkat_prioritas',
        'status',
        'jadwal_konsultasi',
        'catatan_guru',
    ];

    // Casting tipe data (agar jadwal otomatis menjadi objek Carbon/Tanggal)
    protected $casts = [
        'jadwal_konsultasi' => 'datetime',
    ];

    // Relasi ke Model Siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    // Relasi ke Model Guru (BK)
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    // Relasi ke Hasil AI (Eksplorasi)
    public function eksplorasi()
    {
        return $this->belongsTo(Eksplorasi::class, 'id_eksplorasi', 'id_eksplorasi');
    }
}