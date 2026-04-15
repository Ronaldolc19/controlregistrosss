<?php

namespace App\Http\Controllers;

use App\Models\Constancia; 
use App\Models\Liberado; 
use App\Models\Student;
use App\Models\Baja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    
    public function index()
    {
        // 1. KPIs Básicos
        $totalEstudiantes = Student::where('status', '!=', 'BAJA')->count();
        $totalBajas = Baja::count();
        $totalLiberados = Liberado::count();
        $totalConstancias = Constancia::count();

        // 2. Datos para Gráficas
        $constanciasCarrera = Constancia::join('students', 'constancias.id_estudiante', '=', 'students.id_estudiante')
            ->select('students.perfil_profesional_carrera as carrera', DB::raw('count(*) as total'))
            ->groupBy('students.perfil_profesional_carrera')->orderBy('total', 'desc')->take(6)->get();

        $constanciasEmpresa = Constancia::join('students', 'constancias.id_estudiante', '=', 'students.id_estudiante')
            ->select('students.nombre_dependencia_receptora as empresa', DB::raw('count(*) as total'))
            ->groupBy('students.nombre_dependencia_receptora')->orderBy('total', 'desc')->take(5)->get();

        $constanciasPeriodo = Constancia::join('students', 'constancias.id_estudiante', '=', 'students.id_estudiante')
            ->select(DB::raw("CONCAT(students.periodo_inicio, ' - ', students.periodo_termino) as periodo"), DB::raw('count(*) as total'))
            ->groupBy('periodo')->get();

        // 3. Gestión de Históricos (PDFs)
        if (!Storage::disk('public')->exists('historicos')) {
            Storage::disk('public')->makeDirectory('historicos');
        }
        $respaldos = Storage::disk('public')->files('historicos');
        $respaldos = is_array($respaldos) ? $respaldos : [];
        
        if (!empty($respaldos)) {
            usort($respaldos, function($a, $b) {
                return Storage::disk('public')->lastModified($b) <=> Storage::disk('public')->lastModified($a);
            });
        }

        return view('home', compact(
            'totalEstudiantes', 'totalBajas', 'totalLiberados', 'totalConstancias',
            'constanciasCarrera', 'constanciasEmpresa', 'constanciasPeriodo', 'respaldos'
        ));
    }

    public function reiniciarCiclo(Request $request)
    {
        try {
            $totalEst = Student::count();
            // Data Analysis Snapshot
            $data = [
                'fecha_cierre' => now()->format('d/m/Y H:i'),
                'ciclo' => now()->year,
                'total_estudiantes' => Student::where('status', '!=', 'BAJA')->count(),
                'total_liberados' => Liberado::count(),
                'total_bajas' => Baja::count(),
                'por_carrera' => Student::select('perfil_profesional_carrera', DB::raw('count(*) as total'))
                                        ->groupBy('perfil_profesional_carrera')->get(),
                'alumnos' => Student::all() 
            ];

            $pdf = Pdf::loadView('pdf.informe_cierre', $data);
            $nombreArchivo = 'Cierre_Administrativo_' . now()->format('Y_m_d_His') . '.pdf';
            
            Storage::disk('public')->put('historicos/' . $nombreArchivo, $pdf->output());

            return redirect()->back()->with('success', 'Corte administrativo generado exitosamente. Los datos permanecen en el sistema.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error en el reporte: ' . $e->getMessage());
        }
    }

    public function descargarBackupSQL()
    {
        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');
        $dbHost = env('DB_HOST');
        $fecha = now()->format('Y_m_d_His');
        $nombreSql = "Respaldo_Total_{$dbName}_{$fecha}.sql";

        // Comando compatible con Laragon/XAMPP
        $passwordPart = $dbPass ? "--password={$dbPass}" : "";
        $comando = "mysqldump --user={$dbUser} {$passwordPart} --host={$dbHost} {$dbName}";

        return response()->streamDownload(function () use ($comando) {
            passthru($comando);
        }, $nombreSql, ['Content-Type' => 'application/sql']);
    }

}
