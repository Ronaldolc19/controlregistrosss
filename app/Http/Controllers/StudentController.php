<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Liberado;
use App\Models\Baja;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // Listado general (Inscritos)
    public function index()
    {
        $students = Student::orderBy('apellido_paterno', 'asc')->get();
        return view('students.index', compact('students'));
    }

    // Detalle del estudiante
    public function show($id)
    {
        // Cargamos relaciones para mostrar rastro de baja o liberación si existen
        $student = Student::with(['baja', 'liberado'])->findOrFail($id);
        return view('students.show', compact('student'));
    }

    // FORMULARIO DE EDICIÓN
    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return view('students.edit', compact('student'));
    }

    // ACTUALIZAR DATOS
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        // Validación exhaustiva basada en tu nueva vista estructurada
        $request->validate([
            'num_cuenta' => 'required|string|unique:students,num_cuenta,' . $id . ',id_estudiante',
            'nombre' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'perfil_profesional_carrera' => 'required|string',
            'semestre' => 'required|integer|between:1,12',
            'email' => 'nullable|email',
            'curp' => 'nullable|string|size:18',
        ]);

        // Actualizamos todos los campos enviados por el formulario
        $student->update($request->all());

        return redirect()->route('students.index')
            ->with('success', 'Los datos de ' . $student->nombre . ' se han actualizado correctamente.');
    }

    // ELIMINAR REGISTRO
    public function destroy($id)
    {
        $student = Student::findOrFail($id);

        // Limpiamos rastro en tablas relacionales para evitar errores de integridad
        Baja::where('id_estudiante', $id)->delete();
        Liberado::where('id_estudiante', $id)->delete();

        $nombreCompleto = $student->nombre . ' ' . $student->apellido_paterno;
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'El registro de ' . $nombreCompleto . ' ha sido eliminado permanentemente.');
    }

    // --- MÉTODOS DE GESTIÓN DE ESTADOS (Bajas y Liberaciones) ---

    public function indexBajas()
    {
        $bajas = Baja::with('estudiante')->orderBy('fecha_baja', 'desc')->get();
        return view('students.bajas', compact('bajas'));
    }

    public function marcarbaja(Request $request, $id)
    {
        $request->validate([
            'fecha_baja' => 'required|date',
            'motivo_baja' => 'required|string|max:1000',
        ]);

        $student = Student::findOrFail($id);

        Baja::updateOrCreate(
            ['id_estudiante' => $id],
            [
                'registro_estatal_ss' => $student->registro_estatal_ss,
                'fecha_baja' => $request->fecha_baja,
                'motivo_baja' => $request->motivo_baja,
            ]
        );

        $student->status = 'BAJA'; 
        $student->save();

        return redirect()->back()->with('success', 'La baja de ' . $student->nombre . ' ha sido procesada con éxito.');
    }

    public function reactivarEstudiante($id)
    {
        $student = Student::findOrFail($id);
        $student->status = 'Inscripcion'; 
        $student->save();

        Baja::where('id_estudiante', $id)->delete();

        return redirect()->back()->with('success', 'El estudiante ' . $student->nombre . ' ha sido dado de alta nuevamente.');
    }

    public function marcarLiberacion(Request $request, $id)
    {
        $request->validate([
            'modalidad_liberacion' => 'required|string'
        ]);

        $student = Student::findOrFail($id);

        Liberado::create([
            'id_estudiante'   => $student->id_estudiante,
            'modalidad'       => $request->modalidad_liberacion,
            'fecha_liberacion' => now()->format('Y-m-d'),
        ]);

        $student->status = 'LIBERACION';
        $student->save();

        return redirect()->back()->with('success', 'El proceso de liberación se ha registrado correctamente.');
    }

    public function indexLiberados()
    {
        $liberados = Liberado::with('estudiante')->orderBy('id_liberado', 'desc')->get();
        return view('students.liberados_list', compact('liberados'));
    }
}