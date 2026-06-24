<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sekolah extends Model
{
    use SoftDeletes;

    protected $table = 'sekolah';

    protected $primaryKey = 'id_sekolah';

    protected $fillable = [
        'nama_sekolah',
        'alamat',
        'kode_lisensi'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'id_sekolah', 'id_sekolah');
    }
}