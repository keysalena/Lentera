<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kemampuan extends Model
{
    use SoftDeletes;

    protected $table = 'kemampuan';

    protected $primaryKey = 'id_kemampuan';

    protected $fillable = [
        'nama_kemampuan',
    ];

    public function skorKemampuan()
    {
        return $this->hasMany(
            SkorKemampuan::class,
            'id_kemampuan',
            'id_kemampuan'
        );
    }
}