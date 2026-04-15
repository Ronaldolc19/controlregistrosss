<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Liberado; 
use App\Models\Constancia;
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
    public function store(Request $request)
{
    // 1. Guardamos la liberación primero
    $liberacion = new \App\Models\Liberado();
    $liberacion->id_estudiante = $request->id_estudiante;
    $liberacion->modalidad = $request->modalidad;
    $liberacion->fecha_liberacion = $request->fecha_liberacion;
    $liberacion->save();

    // 2. Intentamos guardar la constancia de forma MANUAL (sin fillable)
    // Esto saltará cualquier restricción de protección de datos
    try {
        $nuevaConstancia = new \App\Models\Constancia();
        $nuevaConstancia->id_estudiante = $request->id_estudiante;
        $nuevaConstancia->estado = 'pendiente';
        $nuevaConstancia->pdf_path = null;
        
        if (!$nuevaConstancia->save()) {
            throw new \Exception("Laravel dijo que guardó, pero no hay registro en la DB.");
        }

        return redirect()->route('constancias.index')
            ->with('success', '¡Funcionó! Alumno liberado y en lista de constancias.');

    } catch (\Exception $e) {
        // Si el código llega aquí, verás un error negro con el mensaje exacto
        dd("Error al crear constancia: " . $e->getMessage(), "Datos enviados: ", $request->all());
    }
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
            'modalidad' => 'required|string',
            'fecha_liberacion' => 'required|date',
            
        ]);

        $liberacion = Liberado::where('id_estudiante', $id)->firstOrFail();
        $liberacion->update([
            'modalidad' => $request->modalidad,
            'fecha_liberacion' => $request->fecha_liberacion,
        ]);

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
