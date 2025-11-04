<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';
    protected $primaryKey = 'id_servicio';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'id_tipos_especialidad',
        'nombre',
        'duracion',
        'precio_base',
        'estado',
        'descripcion',
    ];

    // Relación inversa con Specialty
    public function tipoEspecialidad()
    {
        return $this->belongsTo(Specialty::class, 'id_tipos_especialidad', 'id_tipos_especialidad');
    }
}
