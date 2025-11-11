<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    public const STATUS_PROGRAMADA = 'Programada';
    public const STATUS_CANCELADA = 'Cancelada';
    public const STATUS_ATENDIDA = 'Atendida';

    private const STATUS_VARIANTS = [
        self::STATUS_PROGRAMADA => 'warning',
        self::STATUS_CANCELADA => 'primary',
        self::STATUS_ATENDIDA => 'success',
    ];

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

    public static function allowedStatuses(): array
    {
        return [
            self::STATUS_PROGRAMADA,
            self::STATUS_CANCELADA,
            self::STATUS_ATENDIDA,
        ];
    }

    public static function badgeVariant(?string $estado): string
    {
        if (!$estado) {
            return 'neutral';
        }

        return self::STATUS_VARIANTS[$estado] ?? 'neutral';
    }

    public function isProgramada(): bool
    {
        return $this->estado === self::STATUS_PROGRAMADA;
    }

    public function isCancelada(): bool
    {
        return $this->estado === self::STATUS_CANCELADA;
    }

    public function isAtendida(): bool
    {
        return $this->estado === self::STATUS_ATENDIDA;
    }
}
