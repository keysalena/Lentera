<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Siswa extends Model
{
    use SoftDeletes;

    protected $table = 'siswa';

    protected $primaryKey = 'id_siswa';

    protected $fillable = [
        'id_user',
        'nisn',
        'jurusan',
        'angkatan',
        'tanggal_lahir',
        'jenis_kelamin',
        'status_data',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function eksplorasi()
    {
        return $this->hasMany(
            Eksplorasi::class,
            'id_siswa',
            'id_siswa'
        );
    }
}
