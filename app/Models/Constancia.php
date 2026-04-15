<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Constancia extends Model
{
    protected $table = 'constancias';
    protected $primaryKey = 'id_constancia'; // Definición de la nueva PK

    protected $fillable = [
        'id_estudiante', 
        'pdf_path', 
        'estado'
    ];

    public function estudiante()
    {
        return $this->belongsTo(Student::class, 'id_estudiante');
    }
}
