<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Carbon\Carbon;

class StudentImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    /**
     * Convierte el número serial de Excel a objeto de fecha y limpia los ceros
     */
    private function transformDate($value)
    {
        if (empty($value)) return null;
        
        try {
            // Si es un número serial de Excel, lo convertimos y formateamos a Y-m-d
            $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
            return $date->format('Y-m-d'); 
        } catch (\Exception $e) {
            // Si no es serial (es un string como 1/26/2026), usamos Carbon para normalizarlo
            try {
                return Carbon::parse($value)->format('Y-m-d');
            } catch (\Exception $e2) {
                return null;
            }
        }
    }

    public function model(array $row)
    {
        // Si no hay número de cuenta, ignoramos la fila por completo
        if (empty($row['num_de_cuenta'])) {
            return null;
        }

        // Usamos updateOrCreate para evitar duplicados por número de cuenta
        return Student::updateOrCreate(
            ['num_cuenta' => (string)$row['num_de_cuenta']], // Buscador único
            [
                // DATOS DE LA INSTITUCION
                'clave_cct'                => $row[''] ?? '15EIT0013G', 
                'subsistema'               => $row['subsistema'] ?? 'SUPERIOR',
                'nombre_escuela'           => $row['nombre_escuela'],
                'direccion_escuela'        => $row['direccion'],
                'municipio_escuela'        => $row['municipio'],
                'telefono_escuela'         => $row['telefono'],
                'responsable_ss_escuela'   => $row['responsable_del_servicio_social'],
                'correo_escuela'           => $row['correo'],
                'registro_estatal_ss'      => $row['registro_estatal_de_servicio_social'],

                // DATOS DEL ALUMNO
                'apellido_paterno'         => $row['apellido_paterno'],
                'apellido_materno'         => $row['apellido_materno'],
                'nombre'                   => $row['nombre'],
                'semestre'                 => $row['semestre'],
                'nivel'                    => $row['nivel'] ?? 'LICENCIATURA',
                'perfil_profesional_carrera' => $row['perfil_profesional_carrera'],
                
                // Aplicamos la transformación para limpiar los ceros 00:00:00
                'periodo_inicio'           => $this->transformDate($row['periodo_inicio']),
                'periodo_termino'          => $this->transformDate($row['periodo_termino']),
                
                'sexo'                     => $row['sexo'],
                'edad'                     => $row['edad'],
                'promedio'                 => $row['promedio'],
                'porcentaje_cubierto_plan' => isset($row['porcentaje_cubierto_del_plan_de_estudios']) 
                                              ? ($row['porcentaje_cubierto_del_plan_de_estudios'] * 100) . '%' 
                                              : null,

                // DATOS DE LA DEPENDENCIA RECEPTORA
                'nombre_dependencia_receptora' => $row['nombre_de_la_dependencia_receptora_lugar_de_prestacion'],
                'direccion_dependencia'    => $row['direccion'], 
                'municipio_dependencia'    => $row['municipio'],
                'sector'                   => $row['sector'],
                'nombre_responsable_dependencia' => $row['nombre_del_responsable_del_servicio_social'],
                'horario_servicio'         => $row['horario_del_servicio'],
                'proyecto_participa'       => $row['proyecto_en_que_participa_el_pss'],
                'ss_con_o_sin_beca'        => $row['servicio_social_con_estimulo_con_beca_o_sin_beca'],
                'monto_estimulo'           => $row['monto_del_estimulo'],

                // INFORMACIÓN ADICIONAL
                'habla_lengua_indigena'    => $row['habla_alguna_lengua_indigena'],
                'cual_lengua'              => $row['cual'] ?? null,
                'tiene_discapacidad'       => $row['tiene_alguna_discapacidad_sino'],
                'cual_discapacidad'        => $row[34] ?? $row['cual'] ?? 'N/A', 

                'status'                   => 'INSCRIPCION',
            ]
        );
    }

    public function headingRow(): int
    {
        return 5;
    }
}