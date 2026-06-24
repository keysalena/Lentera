<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guru extends Model
{
    use SoftDeletes;

    protected $table = 'guru';

    protected $primaryKey = 'id_guru';

    protected $fillable = [
        'id_user',
        'nip',
        'no_hp',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}