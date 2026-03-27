<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id('id_estudiante'); 
        
        // --- DATOS DE LA INSTITUCION EDUCATIVA ---
        $table->string('clave_cct')->default('15EIT0013G');
        $table->string('subsistema')->default('SUPERIOR');
        $table->string('nombre_escuela');
        $table->text('direccion_escuela');
        $table->string('municipio_escuela');
        $table->string('telefono_escuela');
        $table->string('responsable_ss_escuela');
        $table->string('correo_escuela');
        $table->string('registro_estatal_ss'); // Sin unique por si se repite en el historial

        // --- DATOS DEL PRESTADOR DE SERVICIO SOCIAL ---
        $table->string('apellido_paterno');
        $table->string('apellido_materno');
        $table->string('nombre');
        $table->string('semestre');
        $table->string('num_cuenta')->unique(); // Matrícula (Llave de búsqueda)
        $table->string('nivel')->default('LICENCIATURA');
        $table->string('perfil_profesional_carrera');
        $table->string('periodo_inicio'); // Lo ponemos como string por el formato del Excel 1/26/2026
        $table->string('periodo_termino');
        $table->string('sexo');
        $table->integer('edad');
        $table->string('promedio');
        $table->string('porcentaje_cubierto_plan');

        // --- DATOS DE LA INSTITUCION DONDE SE REALIZA (DEPENDENCIA) ---
        $table->string('nombre_dependencia_receptora');
        $table->text('direccion_dependencia');
        $table->string('municipio_dependencia');
        $table->string('sector'); 
        $table->string('nombre_responsable_dependencia');
        $table->string('horario_servicio');
        $table->string('proyecto_participa');
        $table->string('ss_con_o_sin_beca');
        $table->string('monto_estimulo')->nullable();

        // --- ASPECTOS SOCIALES / VULNERABILIDAD ---
        $table->string('habla_lengua_indigena');
        $table->string('cual_lengua')->nullable();
        $table->string('tiene_discapacidad');
        $table->string('cual_discapacidad')->nullable();

        // --- CONTROL ---
        $table->enum('status', ['INSCRIPCION', 'BAJA', 'LIBERACION'])->default('INSCRIPCION');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
