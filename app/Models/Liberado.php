<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Liberado extends Model
{
    protected $table = 'liberados';
    protected $primaryKey = 'id_liberado'; // Definimos el ID personalizado
    protected $fillable = ['id_estudiante', 'modalidad', 'fecha_liberacion'];

    public function estudiante()
    {
        return $this->belongsTo(Student::class, 'id_estudiante', 'id_estudiante');
    }
}
