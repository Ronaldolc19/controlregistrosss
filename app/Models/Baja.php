<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Baja extends Model
{
    // 1. Nombre de la tabla
    protected $table = 'bajas';

    // 2. Definir la llave primaria personalizada
    protected $primaryKey = 'id_baja';

    // 3. Campos que se pueden llenar masivamente
    protected $fillable = [
        'id_estudiante', 
        'registro_estatal_ss', 
        'fecha_baja', 
        'motivo_baja'
    ];

    // Relación para traer los datos del alumno fácilmente
    public function estudiante()
    {
        return $this->belongsTo(Student::class, 'id_estudiante', 'id_estudiante');
    }
}
