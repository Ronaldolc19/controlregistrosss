<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Liberado; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LiberadoController extends Controller
{
    /**
     * Listado general de alumnos con estatus LIBERADO.
     */
    public function index()
    {
        $liberados = Liberado::with('estudiante')
            ->orderBy('fecha_liberacion', 'desc')
            ->get();

        return view('liberaciones.index', compact('liberados'));
    }

    /**
     * Muestra la ficha técnica de un alumno ya liberado.
     */
    public function show($id)
    {
        $student = Student::findOrFail($id);
        $liberacion = Liberado::where('id_estudiante', $id)->firstOrFail();

        return view('liberaciones.show', compact('student', 'liberacion'));
    }

    /**
     * Formulario de edición para datos de la liberación.
     */
    public function edit($id)
    {
        $student = Student::findOrFail($id);
        $liberacion = Liberado::where('id_estudiante', $id)->firstOrFail();

        return view('liberaciones.edit', compact('student', 'liberacion'));
    }

    /**
     * Actualiza los datos del registro en la tabla liberados.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'fecha_liberacion' => 'required|date',
            'folio_liberacion' => 'required|string|max:100',
            'observaciones'    => 'nullable|string|max:1000',
        ]);

        $liberacion = Liberado::where('id_estudiante', $id)->firstOrFail();
        $liberacion->update($request->only(['fecha_liberacion', 'folio_liberacion', 'observaciones']));

        return redirect()->route('liberaciones.index')
            ->with('success', 'La información de la liberación ha sido actualizada.');
    }

    /**
     * Anula la liberación y regresa al alumno al estado INSCRIPCION.
     */
    public function destroy($id)
    {
        $student = Student::findOrFail($id);

        DB::transaction(function () use ($student, $id) {
            // 1. Revertir estatus en la tabla principal
            $student->status = 'INSCRIPCION';
            $student->save();

            // 2. Eliminar el registro de la tabla liberados
            Liberado::where('id_estudiante', $id)->delete();
        });

        return redirect()->route('liberaciones.index')
            ->with('warning', "Se ha anulado la liberación de {$student->nombre}. El alumno vuelve a estar como Inscrito.");
    }
}
