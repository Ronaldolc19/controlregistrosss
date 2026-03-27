<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Baja;    
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BajaController extends Controller
{
    public function index()
    {
        // Traemos las bajas con la información del estudiante relacionada
        $bajas = Baja::with('estudiante')
            ->orderBy('fecha_baja', 'desc')
            ->get();

        return view('bajas.index', compact('bajas'));
    }
    public function show($id)
    {
        // Obtenemos los datos del estudiante
        $student = Student::findOrFail($id);
        
        // Obtenemos los detalles de la baja para mostrarlos en el expediente
        $baja = Baja::where('id_estudiante', $id)->firstOrFail();

        return view('bajas.show', compact('student', 'baja'));
    }
    // Muestra el formulario de edición
    public function edit($id)
    {
        $student = Student::findOrFail($id);
        $baja = Baja::where('id_estudiante', $id)->firstOrFail();

        return view('bajas.edit', compact('student', 'baja'));
    }

    // Procesa la actualización
    public function update(Request $request, $id)
    {
        $request->validate([
            'fecha_baja' => 'required|date',
            'motivo_baja' => 'required|string|max:1000',
        ]);

        $baja = Baja::where('id_estudiante', $id)->firstOrFail();
        
        $baja->update([
            'fecha_baja' => $request->fecha_baja,
            'motivo_baja' => $request->motivo_baja,
        ]);

        return redirect()->route('bajas.index')
            ->with('success', 'El registro de baja ha sido actualizado correctamente.');
    }
    /**
     * Procesa la baja de un estudiante.
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'fecha_baja' => 'required|date',
            'motivo_baja' => 'required|string|max:1000',
        ]);

        $student = Student::findOrFail($id);

        // Usamos una transacción para asegurar que ambos cambios ocurran o ninguno
        DB::transaction(function () use ($request, $student, $id) {
            // 1. Crear o actualizar el registro en la tabla de bajas
            Baja::updateOrCreate(
                ['id_estudiante' => $id],
                [
                    'registro_estatal_ss' => $student->registro_estatal_ss,
                    'fecha_baja' => $request->fecha_baja,
                    'motivo_baja' => $request->motivo_baja,
                ]
            );

            // 2. Actualizar el estatus en la tabla principal
            $student->status = 'BAJA';
            $student->save();
        });

        return redirect()->route('bajas.index')
            ->with('success', "La baja de {$student->nombre} ha sido procesada correctamente.");
    }

    /**
     * Revierte una baja (Reactivación).
     */
    public function destroy($id)
    {
        $student = Student::findOrFail($id);

        DB::transaction(function () use ($student, $id) {
            // 1. Regresamos al estatus original (puedes ajustarlo a 'INSCRITO' o 'INSCRIPCION')
            $student->status = 'INSCRIPCION'; 
            $student->save();

            // 2. Eliminamos el registro de la tabla de historial de bajas
            Baja::where('id_estudiante', $id)->delete();
        });

        return redirect()->route('students.index')
            ->with('success', "El estudiante {$student->nombre} ha sido reactivado y movido al listado general.");
    }
}
