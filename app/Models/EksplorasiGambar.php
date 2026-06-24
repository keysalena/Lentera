<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EksplorasiGambar extends Model
{
    use SoftDeletes;

    protected $table = 'eksplorasi_gambar';

    protected $primaryKey = 'id_gambar';

    protected $fillable = [
        'id_eksplorasi',
        'gambar',
        'hasil_ocr',
    ];

    public function eksplorasi()
    {
        return $this->belongsTo(
            Eksplorasi::class,
            'id_eksplorasi',
            'id_eksplorasi'
        );
    }
}