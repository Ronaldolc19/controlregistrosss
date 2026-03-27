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
        Schema::create('bajas', function (Blueprint $table) {
            $table->id('id_baja'); // Clave primaria personalizada
        
        // Relación con el estudiante (debe coincidir el tipo de dato)
        $table->unsignedBigInteger('id_estudiante'); 
        
        // Campos solicitados en tu Excel
        $table->string('registro_estatal_ss')->nullable();
        $table->date('fecha_baja');
        $table->text('motivo_baja')->nullable();
        
        $table->timestamps();

        // Definición de la Llave Foránea
        $table->foreign('id_estudiante')
              ->references('id_estudiante') // Tu PK en la tabla students
              ->on('students')
              ->onDelete('cascade'); // Si borras al alumno, se borra su historial de baja
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bajas');
    }
};
