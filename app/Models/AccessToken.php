<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessToken extends Model
{
    use HasFactory;

    protected $table = 'access_tokens';
    protected $primaryKey = 'id_token';
    public $timestamps = true;

    protected $fillable = ['id_usuario', 'token', 'fecha_creacion', 'fecha_expiracion', 'usado'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }
}

