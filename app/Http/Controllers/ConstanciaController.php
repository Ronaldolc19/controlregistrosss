<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Constancia;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConstanciaController extends Controller
{
    public function index(Request $request)
    {
        $query = Constancia::with('estudiante');
        if ($request->has('estado') && $request->estado != '') {
            $query->where('estado', $request->estado);
        }
        $constancias = $query->get();
        // Obtenemos todas las constancias registradas
        //$constancias = \App\Models\Constancia::with('estudiante')->latest()->get();
        return view('constancias.index', compact('constancias'));
    }
    public function store(Request $request)
    {
        // Validamos los datos primero
        $request->validate([
            'id_estudiante' => 'required|exists:estudiantes,id_estudiante',
            'modalidad' => 'required',
            'fecha_liberacion' => 'required|date',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                
                // 1. Guardar en Liberados
                $liberacion = Liberado::create([
                    'id_estudiante'    => $request->id_estudiante,
                    'modalidad'        => $request->modalidad,
                    'fecha_liberacion' => $request->fecha_liberacion,
                ]);

                // 2. Guardar en Constancias
                // IMPORTANTE: Asegúrate de que el modelo Constancia esté bien importado
                Constancia::updateOrCreate(
                    ['id_estudiante' => $request->id_estudiante],
                    [
                        'estado'   => 'pendiente',
                        'pdf_path' => null,
                    ]
                );

                return redirect()->route('constancias.index')
                    ->with('success', 'Liberación exitosa y registro de constancia creado.');
            });
        } catch (\Exception $e) {
            // Si algo falla, esto te dirá EXACTAMENTE qué es (ej. campo faltante, error de SQL)
            return back()->with('error', 'Error al procesar: ' . $e->getMessage());
        }
    }
    public function generarPDF($id_estudiante)
{
    try {
        $estudiante = Student::findOrFail($id_estudiante);
        $constancia = Constancia::firstOrCreate(['id_estudiante' => $id_estudiante]);

        $templatePath = public_path('plantilla/CONSTANCIAUP.docx');
        if (!file_exists($templatePath)) return back()->with('error', 'No existe la plantilla.');

        // 1. Limpiador de espacios agresivo: elimina dobles espacios, saltos de línea y tabulaciones
        $limpiar = function($texto) {
            if (!$texto) return '';
            // Reemplaza múltiples espacios/tabs/news por uno solo y quita orillas
            return preg_replace('/\s+/', ' ', trim($texto));
        };

        // Procesamiento de Nombre
        $nombreCrudo = "{$estudiante->nombre} {$estudiante->apellido_paterno} {$estudiante->apellido_materno}";
        $nombreCompleto = mb_strtoupper($limpiar($nombreCrudo), 'UTF-8');

        // 2. Preparar rutas y nombres
        $folderName = Str::slug($estudiante->periodo_inicio . '_' . $estudiante->periodo_termino);
        $finalDir = storage_path("app/public/constancias/{$folderName}");
        if (!file_exists($finalDir)) mkdir($finalDir, 0755, true);

        $nombreBase = "{$estudiante->num_cuenta}_" . Str::slug($nombreCompleto);
        $pdfPathFinal = "{$finalDir}/{$nombreBase}.pdf";
        $docxPathFinal = "{$finalDir}/{$nombreBase}.docx";

        // 3. Crear el Word Temporal
        $tempFile = storage_path("app/temp_constancia_{$id_estudiante}.docx");
        copy($templatePath, $tempFile);

        // --- LÓGICA DE GÉNERO Y FECHAS ---
        $sexoInput = strtoupper(trim($estudiante->sexo));
        $esFemenino = in_array($sexoInput, ['M', 'MUJER', 'FEMENINO', 'F', 'FEM']);

        Carbon::setLocale('es');
        // Aseguramos que las fechas no tengan espacios raros al parsear
        $f_inicio = Carbon::parse($estudiante->periodo_inicio)->translatedFormat('d \d\e F \d\e Y');
        $f_fin = Carbon::parse($estudiante->periodo_termino)->translatedFormat('d \d\e F \d\e Y');
        $periodoTexto = $f_inicio . " al " . $f_fin;

        $dia = Carbon::now()->format('d'); 
        $mes = Carbon::now()->translatedFormat('F');
        $anio = Carbon::now()->format('Y');
        $emisionTexto = "a los {$dia} días del mes de {$mes} de {$anio}";

        // Importante: mb_strtolower antes de title para que Str::title funcione limpio
        $carrera = Str::title(mb_strtolower($limpiar($estudiante->perfil_profesional_carrera), 'UTF-8'));
        $empresa = Str::title(mb_strtolower($limpiar($estudiante->nombre_dependencia_receptora), 'UTF-8'));

        $variables = [
            '{{NOMBRE}}'        => $nombreCompleto,
            '{{ALU}}'           => $esFemenino ? 'Alumna' : 'Alumno',
            '{{ADSCR}}'         => $esFemenino ? 'adscrita' : 'adscrito',
            '{{CARRERA}}'       => $carrera,
            '{{NO_CUENTA}}'     => $limpiar($estudiante->num_cuenta),
            '{{EMPRESA}}'       => $empresa,
            '{{PERIODO}}'       => $periodoTexto,
            '{{FECHA_EMISION}}' => $emisionTexto,
            '{{NO_REGISTRO}}'   => $limpiar($estudiante->registro_estatal_ss) ?: 'S/N',
        ];

        // 4. Reemplazo de etiquetas en el Word
        $zip = new \ZipArchive;
        if ($zip->open($tempFile) === TRUE) {
            $xml = $zip->getFromName('word/document.xml');
            foreach ($variables as $key => $value) {
                // Reemplazamos la etiqueta. Si en el Word dice " {{NOMBRE}} ", el espacio es del Word.
                // Aquí solo inyectamos el valor limpio.
                $xml = str_replace($key, htmlspecialchars($value, ENT_QUOTES, 'UTF-8'), $xml);
            }
            $zip->addFromString('word/document.xml', $xml);
            $zip->close();
        }

        // 5. Conversión a PDF
        $soffice = '"C:\\Program Files\\LibreOffice\\program\\soffice.exe"';
        $command = "$soffice --headless --convert-to pdf \"$tempFile\" --outdir \"$finalDir\" 2>&1";
        exec($command);

        // 6. Manejo de archivos finales
        $tempPdfName = $finalDir . "/temp_constancia_{$id_estudiante}.pdf";

        if (file_exists($tempPdfName)) {
            if (file_exists($pdfPathFinal)) unlink($pdfPathFinal);
            rename($tempPdfName, $pdfPathFinal);

            if (file_exists($docxPathFinal)) unlink($docxPathFinal);
            rename($tempFile, $docxPathFinal); 

            $constancia->pdf_path = "storage/constancias/{$folderName}/{$nombreBase}.pdf";
            $constancia->estado = 'emitida';
            $constancia->save(); 

            return back()->with('success', "Archivos PDF y Word de {$nombreCompleto} generados con éxito.");
        }

        return back()->with('error', 'Error: LibreOffice no pudo generar el PDF.');

    } catch (\Exception $e) {
        return back()->with('error', 'Error: ' . $e->getMessage());
    }
}
    public function liberarEstudiante($id)
    {
        return DB::transaction(function () use ($id) {
            $estudiante = Estudiante::findOrFail($id);
            
            // 1. Lógica para marcar como liberado (puedes borrarlo de inscritos o cambiar status)
            $estudiante->update(['status' => 'liberado']);

            // 2. CREACIÓN AUTOMÁTICA EN CONSTANCIAS
            // Usamos updateOrCreate para evitar duplicados si re-liberas a alguien
            \App\Models\Constancia::updateOrCreate(
                ['id_estudiante' => $id],
                [
                    'estado' => 'pendiente', 
                    'pdf_path' => null, // Aún no hay archivo
                    'created_at' => now()
                ]
            );

            return redirect()->route('constancias.index')
                ->with('success', 'Estudiante liberado. Ya puedes generar su constancia.');
        });
    }
    public function emitirConstancia($id)
    {
        $constancia = Constancia::findOrFail($id);
        
        // Aquí iría tu lógica actual de generar el Word/PDF con TemplateProcessor
        
        // Al finalizar la generación exitosa, actualizamos:
        $constancia->update([
            'estado' => 'EMITIDO',
            'fecha_emision' => now()
        ]);

        return back()->with('success', 'Constancia emitida y estado actualizado.');
    }
    public function generar($id_estudiante)
    {
        $constancia = Constancia::where('id_estudiante', $id_estudiante)->firstOrFail();
        
        // 1. Aquí va tu lógica de TemplateProcessor (PHPWord) para crear el DOCX/PDF
        // $path = $this->generarArchivoFisico($id_estudiante);

        // 2. ACTUALIZACIÓN AUTOMÁTICA DE ESTADO
        $constancia->update([
            'estado' => 'emitida',
            'pdf_path' => 'storage/constancias/constancia_'.$id_estudiante.'.pdf', // Ejemplo de ruta
            'updated_at' => now()
        ]);

        return back()->with('success', '¡Constancia emitida y estado actualizado!');
    }
}
