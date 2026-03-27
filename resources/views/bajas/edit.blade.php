@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    <li class="breadcrumb-item"><a href="{{ route('students.bajas.list') }}" class="text-decoration-none text-danger">Bajas</a></li>
                    <li class="breadcrumb-item active">Editar Registro de Baja</li>
                </ol>
            </nav>
            <h2 class="fw-bold text-dark mb-0">
                <i class="bi bi-pencil-square me-2 text-warning"></i>Modificar Baja: <span class="text-muted">{{ $student->num_cuenta }}</span>
            </h2>
        </div>
        <a href="{{ route('students.bajas.list') }}" class="btn btn-outline-secondary rounded-3 px-4">
            <i class="bi bi-x-circle me-1"></i> Cancelar
        </a>
    </div>

    <div class="row g-4 justify-content-center">
        <div class="col-lg-8">
            <div class="card bento-card border-0 shadow-sm overflow-hidden">
                <div class="bg-warning py-2 text-center text-white fw-bold small text-uppercase" style="letter-spacing: 1px;">
                    Edición de Historial de Salida
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-4">
                        <div class="avatar-md bg-white text-danger rounded-circle shadow-sm d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                            <i class="bi bi-person-slash"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">{{ $student->nombre }} {{ $student->apellido_paterno }}</h5>
                            <span class="badge bg-danger-subtle text-danger rounded-pill">Estatus: BAJA ACTIVA</span>
                        </div>
                    </div>

                    <form action="{{ route('bajas.update', $baja->id_estudiante) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            {{-- Fecha de Baja --}}
                            <div class="col-md-6">
                                <label for="fecha_baja" class="form-label fw-bold text-muted small text-uppercase">Fecha de la Baja</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-calendar-event"></i></span>
                                    <input type="date" name="fecha_baja" id="fecha_baja" 
                                           class="form-control border-start-0 @error('fecha_baja') is-invalid @enderror" 
                                           value="{{ old('fecha_baja', $baja->fecha_baja) }}" required>
                                </div>
                                @error('fecha_baja')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Registro SS (Solo lectura para referencia) --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Registro Estatal S.S.</label>
                                <input type="text" class="form-control bg-light" value="{{ $student->registro_estatal_ss }}" disabled title="Este dato se edita en el expediente general">
                            </div>

                            {{-- Motivo de la Baja --}}
                            <div class="col-12">
                                <label for="motivo_baja" class="form-label fw-bold text-muted small text-uppercase">Motivo Detallado de la Baja</label>
                                <textarea name="motivo_baja" id="motivo_baja" rows="5" 
                                          class="form-control @error('motivo_baja') is-invalid @enderror" 
                                          placeholder="Explique las razones de la suspensión del servicio..." required>{{ old('motivo_baja', $baja->motivo_baja) }}</textarea>
                                <div class="form-text text-muted">Esta información aparecerá en los reportes de control escolar.</div>
                                @error('motivo_baja')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 pt-3">
                                <hr class="opacity-10 mb-4">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="submit" class="btn btn-warning rounded-3 px-5 fw-bold shadow-sm">
                                        <i class="bi bi-check2-circle me-1"></i> Actualizar Información
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bento-card { border-radius: 20px; }
    .form-control { border-radius: 10px; padding: 10px 15px; border-color: #eee; }
    .form-control:focus { box-shadow: 0 0 0 0.25 margin-bottom: 0; outline: none; border-color: #ffc107; }
    .input-group-text { border-radius: 10px 0 0 10px; border-color: #eee; }
    .bg-danger-subtle { background-color: #fceaea !important; }
</style>
@endsection