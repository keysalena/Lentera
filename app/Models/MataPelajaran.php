<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MataPelajaran extends Model
{
    use SoftDeletes;

    protected $table = 'mata_pelajaran';

    protected $primaryKey = 'id_mapel';

    protected $fillable = [
        'nama_mapel',
    ];

    public function nilaiAkademik()
    {
        return $this->hasMany(
            NilaiAkademik::class,
            'id_mapel',
            'id_mapel'
        );
    }
}