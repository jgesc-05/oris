<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    use HasFactory;

    protected $table = 'document_type';
    protected $primaryKey = 'id_tipo_documento';
    public $timestamps = false;

    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasMany(User::class, 'id_tipo_documento', 'id_tipo_documento');
    }
}

