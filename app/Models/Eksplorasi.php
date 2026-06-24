<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Eksplorasi extends Model
{
    use SoftDeletes;

    protected $table = 'eksplorasi';

    protected $primaryKey = 'id_eksplorasi';

    protected $fillable = [
        'id_siswa',
        'status',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    public function gambar()
    {
        return $this->hasMany(
            EksplorasiGambar::class,
            'id_eksplorasi',
            'id_eksplorasi'
        );
    }

    public function nilaiAkademik()
    {
        return $this->hasMany(
            NilaiAkademik::class,
            'id_eksplorasi',
            'id_eksplorasi'
        );
    }

    public function skorKemampuan()
    {
        return $this->hasMany(
            SkorKemampuan::class,
            'id_eksplorasi',
            'id_eksplorasi'
        );
    }
}