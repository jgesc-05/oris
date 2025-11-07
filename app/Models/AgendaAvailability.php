<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaAvailability extends Model
{
    use HasFactory;

    protected $table = 'agenda_availability';
    protected $primaryKey = 'id_disponibilidad';
    public $timestamps = false;

    protected $fillable = [
        'hora_inicio',
        'hora_fin',
        'dia_semana',
        'vigencia_desde',
        'vigencia_hasta',
        'id_usuario_odontologo',
    ];
}
