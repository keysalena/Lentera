<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NilaiAkademik extends Model
{
    use SoftDeletes;

    protected $table = 'nilai_akademik';

    protected $primaryKey = 'id_nilai';

    protected $fillable = [
        'id_eksplorasi',
        'id_mapel',
        'nilai',
    ];

    protected function casts(): array
    {
        return [
            'nilai' => 'decimal:2',
        ];
    }

    public function eksplorasi()
    {
        return $this->belongsTo(
            Eksplorasi::class,
            'id_eksplorasi',
            'id_eksplorasi'
        );
    }

    public function mapel()
    {
        return $this->belongsTo(
            MataPelajaran::class,
            'id_mapel',
            'id_mapel'
        );
    }
}