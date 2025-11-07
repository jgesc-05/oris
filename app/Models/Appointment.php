<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $table = 'appointments';
    protected $primaryKey = 'id_cita';

    protected $fillable = [
        'id_usuario_paciente',
        'id_usuario_medico',
        'id_servicio',
        'id_usuario_agenda',
        'id_usuario_cancela',
        'fecha_hora_inicio',
        'fecha_hora_fin',
        'estado',
        'notas',
        'motivo_cancelacion',
    ];

    protected $casts = [
        'fecha_hora_inicio' => 'datetime',
        'fecha_hora_fin' => 'datetime',
    ];

    public function paciente()
    {
        return $this->belongsTo(User::class, 'id_usuario_paciente', 'id_usuario');
    }

    public function medico()
    {
        return $this->belongsTo(User::class, 'id_usuario_medico', 'id_usuario');
    }

    public function servicio()
    {
        return $this->belongsTo(Service::class, 'id_servicio', 'id_servicio');
    }
}
