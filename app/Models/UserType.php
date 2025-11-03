<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    use HasFactory;

    protected $table = 'user_types';
    protected $primaryKey = 'id_tipo_usuario';
    public $timestamps = false;

    protected $fillable = ['nombre'];

    public function users()
    {
        return $this->hasMany(User::class, 'id_tipo_usuario', 'id_tipo_usuario');
    }
}

