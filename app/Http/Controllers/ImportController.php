<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\StudentImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
    public function index()
    {
        return view('import.index');
    }

    /**
     * Procesa la carga del dataset de Inscripción
     */
    public function importInscripcion(Request $request) 
    {
        // Validamos que llegue el archivo 'file' (como pusimos en el modal)
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls|max:10240',
        ]);

        try {
            DB::beginTransaction();

            Excel::import(new StudentImport, $request->file('file'));

            DB::commit();
            return back()->with('success', '¡Dataset cargado! Se procesaron los registros válidos.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            // Esto te mostrará el error real en pantalla para debuguear
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
