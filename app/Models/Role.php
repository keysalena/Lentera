<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'nama_role'
])]
class Role extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id_role';

    public function users()
    {
        return $this->hasMany(User::class, 'id_role', 'id_role');
    }
}