<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_usuario';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_tipo_usuario',
        'id_tipo_documento',
        'numero_documento',
        'correo_electronico',
        'telefono',
        'estado',
        'nombres',
        'apellidos',
        'fecha_nacimiento',
        'fecha_ingreso_ips',
        'fecha_creacion_sistema',
        'observaciones',
        'password',
    ];

    public $timestamps = true;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relaciones
    public function userType()
    {
        return $this->belongsTo(UserType::class, 'id_tipo_usuario', 'id_tipo_usuario');
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class, 'id_tipo_documento', 'id_tipo_documento');
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'id_usuario', 'id_usuario');
    }

    public function tokens()
    {
        return $this->hasMany(AccessToken::class, 'id_usuario', 'id_usuario');
    }

    // Hash automático al guardar contraseña
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = bcrypt($value);
        }
    }
}

