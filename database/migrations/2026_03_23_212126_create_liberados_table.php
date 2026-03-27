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
        Schema::create('liberados', function (Blueprint $table) {
            $table->id('id_liberado'); // Identificador único solicitado
            $table->unsignedBigInteger('id_estudiante');
            $table->string('modalidad'); 
            $table->date('fecha_liberacion');
            $table->timestamps();

            // Relación con la tabla de estudiantes
            $table->foreign('id_estudiante')->references('id_estudiante')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('liberados');
    }
};
