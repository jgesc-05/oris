<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleBlock extends Model
{
    use HasFactory;

    protected $table = 'schedule_blocks';
    protected $primaryKey = 'id_bloque';

    protected $fillable = [
        'medico_id',
        'fecha',
        'hora_desde',
        'hora_hasta',
        'motivo',
        'created_by',
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora_desde' => 'datetime:H:i:s',
        'hora_hasta' => 'datetime:H:i:s',
    ];

    public function medico()
    {
        return $this->belongsTo(User::class, 'medico_id', 'id_usuario');
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'created_by', 'id_usuario');
    }
}
