<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $table = 'doctors';
    protected $primaryKey = 'id_usuario';
    public $timestamps = true;

    protected $fillable = [
        'id_usuario',
        'id_tipos_especialidad',
        'universidad',
        'numero_licencia',
        'descripcion',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class, 'id_tipos_especialidad', 'id_tipos_especialidad');
    }

    public function tipoEspecialidad()
    {
        return $this->specialty();
    }
}
