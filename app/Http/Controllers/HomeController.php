<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Baja;
use Illuminate\Http\Request;

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
        // 1. Estudiantes activos (los que no son baja)
        $totalEstudiantes = Student::where('status', '!=', 'BAJA')->count();

        // 2. Bajas totales (desde nuestra nueva tabla de bajas)
        $totalBajas = Baja::count();

        // 3. Liberados (estudiantes con estatus LIBERADO)
        $totalLiberados = Student::where('status', 'LIBERADO')->count();

        // Corregido: Solo pasamos los nombres de las variables como strings
        return view('home', compact('totalEstudiantes', 'totalBajas', 'totalLiberados'));
    
    }
}
