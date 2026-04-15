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
        Schema::create('constancias', function (Blueprint $table) {
            $table->id('id_constancia'); // Llave primaria personalizada
            $table->unsignedBigInteger('id_estudiante');
            $table->string('pdf_path')->nullable();
            $table->enum('estado', ['pendiente', 'emitida'])->default('pendiente');
            $table->timestamps();

            // Relación con tu tabla existente
            $table->foreign('id_estudiante')->references('id_estudiante')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('constancias');
    }
};
