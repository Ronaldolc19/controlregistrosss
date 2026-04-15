<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Liberado;
use App\Models\Baja;
use App\Models\Constancia;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // Listado general (Inscritos)
    public function index()
    {
        $students = Student::orderBy('apellido_paterno', 'asc')->get();
        return view('students.index', compact('students'));
    }
    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Identificación única
            'num_cuenta' => 'required|string|unique:students,num_cuenta',
            
            // Datos Institución
            'clave_cct' => 'required|string',
            'subsistema' => 'required|string',
            'nombre_escuela' => 'required|string',
            'direccion_escuela' => 'required|string',
            'municipio_escuela' => 'required|string',
            'telefono_escuela' => 'required|string',
            'responsable_ss_escuela' => 'required|string',
            'correo_escuela' => 'required|email',
            'registro_estatal_ss' => 'required|string',

            // Datos Alumno
            'apellido_paterno' => 'required|string',
            'apellido_materno' => 'required|string',
            'nombre' => 'required|string',
            'semestre' => 'required|string',
            'nivel' => 'required|string',
            'perfil_profesional_carrera' => 'required|string',
            'periodo_inicio' => 'required|string',
            'periodo_termino' => 'required|string',
            'sexo' => 'required|string',
            'edad' => 'required|integer',
            'promedio' => 'required|string',
            'porcentaje_cubierto_plan' => 'required|string',

            // Datos Dependencia
            'nombre_dependencia_receptora' => 'required|string',
            'direccion_dependencia' => 'required|string',
            'municipio_dependencia' => 'required|string',
            'sector' => 'required|string',
            'nombre_responsable_dependencia' => 'required|string',
            'horario_servicio' => 'required|string',
            'proyecto_participa' => 'required|string',
            'ss_con_o_sin_beca' => 'required|string',
            'monto_estimulo' => 'nullable|string', // Permite nulo según tu SQL

            // Información Social
            'habla_lengua_indigena' => 'required|string',
            'cual_lengua' => 'nullable|string',
            'tiene_discapacidad' => 'required|string',
            'cual_discapacidad' => 'nullable|string',
            'status' => 'INSCRIPCION',
        ]);

        Student::create($validated);

        return redirect()->route('students.index')->with('success', 'Estudiante registrado correctamente.');
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
    public function update(Request $request, $id_estudiante)
    {
        // 1. Localizar al estudiante
        $student = Student::where('id_estudiante', $id_estudiante)->firstOrFail();

        // 2. Validación exhaustiva basada en tu esquema (evita errores de SQL 'Field cannot be null')
        $request->validate([
            // Datos Escuela
            'clave_cct' => 'required|string|max:255',
            'subsistema' => 'required|string|max:255',
            'nombre_escuela' => 'required|string|max:255',
            'direccion_escuela' => 'required|string',
            'municipio_escuela' => 'required|string|max:255',
            'telefono_escuela' => 'required|string|max:255',
            'responsable_ss_escuela' => 'required|string|max:255',
            'correo_escuela' => 'required|email|max:255',
            
            // Datos Alumno
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'num_cuenta' => 'required|string|unique:students,num_cuenta,' . $student->id_estudiante . ',id_estudiante',
            'registro_estatal_ss' => 'required|string|max:255',
            'nivel' => 'required|string|max:255',
            'semestre' => 'required|string|max:255',
            'perfil_profesional_carrera' => 'required|string|max:255',
            'periodo_inicio' => 'required|string|max:255',
            'periodo_termino' => 'required|string|max:255',
            'sexo' => 'required|string|max:255',
            'edad' => 'required|integer|min:15',
            'promedio' => 'required|string|max:255',
            'porcentaje_cubierto_plan' => 'required|string|max:255',

            // Dependencia
            'nombre_dependencia_receptora' => 'required|string|max:255',
            'direccion_dependencia' => 'required|string',
            'municipio_dependencia' => 'required|string|max:255',
            'sector' => 'required|string|max:255',
            'nombre_responsable_dependencia' => 'required|string|max:255',
            'horario_servicio' => 'required|string|max:255',
            'proyecto_participa' => 'required|string|max:255',
            'ss_con_o_sin_beca' => 'required|string|max:255',
            'monto_estimulo' => 'nullable|string|max:255', // Permite NULL según tu tabla

            // Socio-cultural
            'habla_lengua_indigena' => 'required|string|max:255',
            'cual_lengua' => 'nullable|string|max:255', // Permite NULL
            'tiene_discapacidad' => 'required|string|max:255',
            'cual_discapacidad' => 'required|string|max:255',
        ]);

        try {
            // 3. Actualización masiva
            $student->update($request->all());

            return redirect()->route('students.index')
                            ->with('success', 'Expediente de ' . $student->nombre . ' actualizado correctamente.');

        } catch (\Exception $e) {
            // Captura errores de base de datos (por ejemplo, si falta un campo que pusimos como NO NULL)
            return back()->withInput()->withErrors(['db_error' => 'Error técnico: ' . $e->getMessage()]);
        }
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
    public function liberarManual(Request $request, $id)
    {
        $request->validate([
            'modalidad_liberacion' => 'required'
        ]);

        $student = Student::findOrFail($id);

        try {
            DB::beginTransaction();

            // 1. Cambiamos el estatus del alumno
            $student->update(['status' => 'LIBERACION']);

            // 2. Registramos la liberación
            Liberado::create([
                'id_estudiante' => $student->id_estudiante,
                'modalidad'     => $request->modalidad_liberacion,
                'fecha_liberacion' => now(),
            ]);

            // 3. Creamos la constancia en estado PENDIENTE
            // Asegúrate de que los nombres de las columnas coincidan con tu base de datos
            Constancia::create([
                'id_estudiante'   => $student->id_estudiante,
                'estado_proceso'  => 'PENDIENTE', 
                'tipo_documento'  => 'CONSTANCIA DE LIBERACION',
                'fecha_registro'  => now(),
            ]);

            DB::commit();
            return redirect()->route('students.index')->with('success', 'Liberación procesada y constancia en pendientes.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al procesar la liberación: ' . $e->getMessage());
        }
    }
}