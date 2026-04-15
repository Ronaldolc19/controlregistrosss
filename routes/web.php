<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\BajaController;
use App\Http\Controllers\LiberadoController;
use App\Http\Controllers\ConstanciaController;

// --- ACCESO PÚBLICO ---
Route::get('/', function () {
    return view('auth.login');
});

// Desactivamos el registro público para que solo el admin creado por Seeder tenga acceso
Auth::routes(['register' => false]);

// --- ACCESO RESTRINGIDO (SOLO ADMINISTRADOR) ---
Route::middleware(['auth'])->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // --- MÓDULO DE IMPORTACIÓN (Excel/CSV) ---
    Route::controller(ImportController::class)->group(function () {
        Route::post('/importar-inscripcion', 'importInscripcion')->name('import.inscripcion');
        Route::post('/importar-bajas', 'importBajas')->name('import.bajas');
        Route::post('/importar-liberaciones', 'importLiberaciones')->name('import.liberacion');
    });

    // --- MÓDULO DE ESTUDIANTES (Inscritos) ---
    // El Resource ya incluye index, create, store, show, edit, update, destroy
    Route::resource('students', StudentController::class);
    
    // Acciones específicas de Estudiantes
    Route::controller(StudentController::class)->group(function () {
        Route::post('/students/{id}/reactivar', 'reactivarEstudiante')->name('students.reactivar');
        Route::post('/students/{id}/liberar', 'liberarManual')->name('students.liberar.manual');
        Route::post('/students/{id}/baja', 'marcarBaja')->name('students.baja.manual');
    });

    // --- MÓDULO DE BAJAS ---
    Route::resource('bajas', BajaController::class)->except(['create']);

    // --- MÓDULO DE LIBERACIONES ---
    Route::resource('liberaciones', LiberadoController::class)->except(['create']);

    // --- MÓDULO DE CONSTANCIAS ---
    Route::controller(ConstanciaController::class)->group(function () {
        Route::get('/constancias', 'index')->name('constancias.index');
        Route::get('/constancia/generar/{id_estudiante}', 'generarPDF')->name('constancia.generar');
    });

    Route::post('/sistema/reiniciar', [HomeController::class, 'reiniciarCiclo'])->name('sistema.reiniciar');
    Route::get('/sistema/backup-sql', [HomeController::class, 'descargarBackupSQL'])->name('sistema.backup.sql');

});