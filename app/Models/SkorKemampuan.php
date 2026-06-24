<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SkorKemampuan extends Model
{
    use SoftDeletes;

    protected $table = 'skor_kemampuan';

    protected $primaryKey = 'id_skor';

    protected $fillable = [
        'id_eksplorasi',
        'id_kemampuan',
        'skor',
    ];

    protected function casts(): array
    {
        return [
            'skor' => 'integer',
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

    public function kemampuan()
    {
        return $this->belongsTo(
            Kemampuan::class,
            'id_kemampuan',
            'id_kemampuan'
        );
    }
}