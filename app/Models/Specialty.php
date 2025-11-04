<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Specialty extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'specialty_type';
    protected $primaryKey = 'id_tipos_especialidad';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    public function services()
    {
        return $this->hasMany(Service::class, 'id_tipos_especialidad', 'id_tipos_especialidad');
    }

    // Relación muchos a muchos con Doctor
    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'doctors_specialties', 'id_tipos_especialidad', 'id_usuario_doctor');
    }

}
