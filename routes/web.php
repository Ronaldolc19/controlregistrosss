<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\BajaController;
use App\Http\Controllers\LiberadoController;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/importar-inscripcion', [ImportController::class, 'importInscripcion'])
        ->name('import.inscripcion');
// Ruta para cargar el Dataset de Bajas
Route::post('/importar-bajas', [ImportController::class, 'importBajas'])
    ->name('import.bajas');

// Ruta para cargar el Dataset de Liberaciones (Opcional por ahora)
Route::post('/importar-liberaciones', [ImportController::class, 'importLiberaciones'])
    ->name('import.liberacion');

    // --- MÓDULO DE ESTUDIANTES ---
    // Listado, Ver, Editar y Eliminar
Route::resource('students', StudentController::class);
Route::post('/students/{id}/baja', [StudentController::class, 'marcarBaja'])->name('students.baja.manual');
Route::post('/students/{id}/liberar', [StudentController::class, 'marcarLiberacion'])->name('students.liberar.manual');
Route::get('/alumnos/liberados', [StudentController::class, 'indexLiberados'])->name('students.liberados.list');
Route::get('/alumnos/bajas', [StudentController::class, 'indexBajas'])->name('students.bajas.list');
Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    
// Apartado de Bajas
Route::get('/students/bajas', [StudentController::class, 'indexBajas'])->name('students.bajas.list');

// Acción de Baja Manual
Route::post('/students/{id}/baja-manual', [StudentController::class, 'marcarBaja'])->name('students.baja.manual');

// Ver expediente individual
Route::get('/students/{id}', [StudentController::class, 'show'])->name('students.show');
Route::post('/students/{id}/reactivar', [StudentController::class, 'reactivarEstudiante'])->name('students.reactivar');
Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');

// Ruta para procesar la actualización de los datos (usa PUT o PATCH)
Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');
// Listado de bajas
Route::get('/bajas', [BajaController::class, 'index'])->name('bajas.index');

// Procesar una nueva baja (Desde el expediente del alumno)
Route::post('/bajas/{id}', [BajaController::class, 'store'])->name('bajas.store');

// Reactivar estudiante (Eliminar de la tabla de bajas)
Route::delete('/bajas/{id}', [BajaController::class, 'destroy'])->name('bajas.destroy');
Route::get('/liberaciones', [StudentController::class, 'indexLiberados'])->name('students.liberados');
Route::get('/bajas/{id}', [BajaController::class, 'show'])->name('bajas.show');
Route::get('/bajas/{id}/edit', [BajaController::class, 'edit'])->name('bajas.edit');
Route::put('/bajas/{id}', [BajaController::class, 'update'])->name('bajas.update');
Route::get('/liberaciones', [LiberadoController::class, 'index'])->name('liberaciones.index');
// Ver detalle, editar y eliminar
Route::get('/liberaciones/{id}', [LiberadoController::class, 'show'])->name('liberaciones.show');
Route::get('/liberaciones/{id}/edit', [LiberadoController::class, 'edit'])->name('liberaciones.edit');
Route::put('/liberaciones/{id}', [LiberadoController::class, 'update'])->name('liberaciones.update');
Route::delete('/liberaciones/{id}', [LiberadoController::class, 'destroy'])->name('liberaciones.destroy');



