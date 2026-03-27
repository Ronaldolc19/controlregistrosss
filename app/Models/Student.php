<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    // Indicamos la nueva llave primaria
    protected $primaryKey = 'id_estudiante';

    protected $fillable = [
        'clave_cct', 'subsistema', 'nombre_escuela', 'direccion_escuela', 'municipio_escuela',
        'telefono_escuela', 'responsable_ss_escuela', 'correo_escuela', 'registro_estatal_ss',
        'apellido_paterno', 'apellido_materno', 'nombre', 'semestre', 'num_cuenta', 'nivel',
        'perfil_profesional_carrera', 'periodo_inicio', 'periodo_termino', 'sexo', 'edad',
        'promedio', 'porcentaje_cubierto_plan', 'nombre_dependencia_receptora',
        'direccion_dependencia', 'municipio_dependencia', 'sector', 
        'nombre_responsable_dependencia', 'horario_servicio', 'proyecto_participa',
        'ss_con_o_sin_beca', 'monto_estimulo', 'habla_lengua_indigena', 'cual_lengua',
        'tiene_discapacidad', 'cual_discapacidad', 'status'
    ];
    public function baja()
    {
        // Relación de 1 a 1: Un estudiante tiene una entrada en la tabla de bajas
        // Ajusta 'id_estudiante' si tu llave foránea en la tabla bajas se llama distinto
        return $this->hasOne(Baja::class, 'id_estudiante', 'id_estudiante');
    }
    public function liberado()
    {
        // Un estudiante tiene un registro de liberación
        return $this->hasOne(Liberado::class, 'id_estudiante', 'id_estudiante');
    }
}
