@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card bento-card border-0 shadow-sm overflow-hidden">
                <div class="bg-success py-2 text-center text-white fw-bold small text-uppercase" style="letter-spacing: 1px;">
                    Corregir Registro de Liberación
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="avatar-md bg-success-subtle text-success rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                            <i class="bi bi-pencil-fill fs-3"></i>
                        </div>
                        <h5 class="fw-bold mb-0 text-dark">{{ $student->nombre }} {{ $student->apellido_paterno }}</h5>
                        <span class="badge bg-light text-muted border px-3 rounded-pill">Cuenta: {{ $student->num_cuenta }}</span>
                    </div>

                    <form action="{{ route('liberaciones.update', $liberacion->id_estudiante) }}" method="POST">
                        @csrf 
                        @method('PUT')
                        
                        {{-- Selector de Modalidad basado en tu imagen --}}
                        <div class="mb-3">
                            <label for="modalidad" class="form-label fw-bold text-muted small text-uppercase">Modalidad de Liberación</label>
                            <select name="modalidad" id="modalidad" class="form-select @error('modalidad') is-invalid @enderror" required>
                                <option value="" disabled>Seleccione una opción...</option>
                                <option value="LIBERACION POR CONCLUCION DE SERVICIO SOCIAL" {{ old('modalidad', $liberacion->modalidad) == 'LIBERACION POR CONCLUCION DE SERVICIO SOCIAL' ? 'selected' : '' }}>
                                    LIBERACIÓN POR CONCLUCIÓN DE SERVICIO SOCIAL
                                </option>
                                <option value="LIBERACIÓN ARTICULO 21 ESTATAL-91 FEDERAL" {{ old('modalidad', $liberacion->modalidad) == 'LIBERACIÓN ARTICULO 21 ESTATAL-91 FEDERAL' ? 'selected' : '' }}>
                                    LIBERACIÓN ARTÍCULO 21 ESTATAL-91 FEDERAL
                                </option>
                                <option value="LIBERACIÓN ARTICULO 19" {{ old('modalidad', $liberacion->modalidad) == 'LIBERACIÓN ARTICULO 19' ? 'selected' : '' }}>
                                    LIBERACIÓN ARTÍCULO 19
                                </option>
                            </select>
                            @error('modalidad')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Fecha de Liberación --}}
                        <div class="mb-4">
                            <label for="fecha_liberacion" class="form-label fw-bold text-muted small text-uppercase">Fecha de Término</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-calendar-check"></i></span>
                                <input type="date" name="fecha_liberacion" id="fecha_liberacion" 
                                       class="form-control border-start-0 @error('fecha_liberacion') is-invalid @enderror" 
                                       value="{{ old('fecha_liberacion', $liberacion->fecha_liberacion) }}" required>
                            </div>
                            @error('fecha_liberacion')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success rounded-3 fw-bold py-2 shadow-sm">
                                <i class="bi bi-check-circle me-1"></i> Actualizar Registro
                            </button>
                            <a href="{{ route('liberaciones.index') }}" class="btn btn-link text-muted text-decoration-none btn-sm">
                                Cancelar y volver
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="mt-3 text-center">
                <p class="text-muted small italic">
                    <i class="bi bi-info-circle me-1"></i> 
                    Solo modifica estos datos si hubo un error en la captura inicial.
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    .bento-card { border-radius: 20px; }
    .form-select, .form-control { border-radius: 10px; padding: 10px 15px; border-color: #eee; }
    .form-select:focus, .form-control:focus { border-color: #198754; box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.1); }
    .input-group-text { border-radius: 10px 0 0 10px; border-color: #eee; }
    .bg-success-subtle { background-color: #e8f5e9 !important; }
</style>
@endsection