@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    {{-- Cambiado para regresar al historial de bajas --}}
                    <li class="breadcrumb-item"><a href="{{ route('students.bajas.list') }}" class="text-decoration-none">Historial de Bajas</a></li>
                    <li class="breadcrumb-item active">Detalle de Baja</li>
                </ol>
            </nav>
            <h2 class="fw-bold text-dark mb-0">
                <i class="bi bi-person-vcard me-2 text-danger"></i>Expediente: <span class="text-danger">{{ $student->num_cuenta }}</span>
            </h2>
        </div>
        <div>
            {{-- Botón de regreso corregido para ir a bajas --}}
            <a href="{{ route('bajas.index') }}" class="btn btn-dark rounded-3 px-4 shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Regresar a Bajas
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-4 col-lg-5">
            {{-- Card de Perfil --}}
            <div class="card bento-card border-0 shadow-sm p-4 text-center mb-4 border-top border-danger border-4">
                <div class="mx-auto mb-3 bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                    <i class="bi bi-person-fill text-danger opacity-50" style="font-size: 4rem;"></i>
                </div>
                <h4 class="fw-bold mb-1 text-uppercase">{{ $student->nombre }}</h4>
                <h5 class="fw-bold text-muted mb-3">{{ $student->apellido_paterno }} {{ $student->apellido_materno }}</h5>
                
                <div class="d-flex justify-content-center mb-4">
                    <span class="badge bg-danger rounded-pill px-4 py-2 shadow-sm">ESTADO: BAJA</span>
                </div>
                
                <div class="text-start border-top pt-3">
                    <p class="small text-muted text-uppercase fw-bold mb-2 text-danger">Información de la Baja</p>
                    <div class="p-3 bg-danger-subtle rounded-3 mb-3 border border-danger border-opacity-10">
                        <div class="d-flex justify-content-between mb-2 small">
                            <span>Fecha de Salida:</span> 
                            <span class="fw-bold">{{ \Carbon\Carbon::parse($baja->fecha_baja)->format('d/m/Y') }}</span>
                        </div>
                        <label class="small text-muted d-block">Motivo reportado:</label>
                        <p class="small fw-bold mb-0 text-dark">{{ $baja->motivo_baja }}</p>
                    </div>
                </div>
            </div>

            {{-- Información Personal --}}
            <div class="card bento-card border-0 shadow-sm p-4">
                <h6 class="fw-bold mb-3 text-uppercase text-muted small"><i class="bi bi-info-circle me-2"></i>Información Personal</h6>
                <div class="row g-3">
                    <div class="col-6">
                        <label class="text-muted extra-small d-block text-uppercase">Sexo</label>
                        <span class="fw-bold small">{{ $student->sexo }}</span>
                    </div>
                    <div class="col-6">
                        <label class="text-muted extra-small d-block text-uppercase">Edad</label>
                        <span class="fw-bold small">{{ $student->edad }} años</span>
                    </div>
                    <div class="col-12">
                        <label class="text-muted extra-small d-block text-uppercase">CURP</label>
                        <span class="fw-bold small">{{ $student->curp ?? 'No registrado' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7">
            {{-- Trayectoria Académica --}}
            <div class="card bento-card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 text-primary"><i class="bi bi-mortarboard me-2"></i>Trayectoria Académica</h5>
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="text-muted small d-block">Programa Educativo / Carrera</label>
                            <div class="p-3 bg-light rounded-3 fw-bold border-start border-primary border-4">
                                {{ $student->perfil_profesional_carrera }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Registro Estatal S.S.</label>
                            <span class="fw-bold">{{ $student->registro_estatal_ss }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Clave CCT</label>
                            <span class="fw-bold">{{ $student->clave_cct }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Periodo que cursaba</label>
                            <span class="fw-bold">{{ $student->periodo_inicio }} - {{ $student->periodo_termino }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Subsistema</label>
                            <span class="fw-bold">{{ $student->subsistema }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Dependencia --}}
            <div class="card bento-card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 text-primary"><i class="bi bi-building me-2"></i>Dependencia donde estaba</h5>
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="text-muted small d-block">Nombre de la Dependencia</label>
                            <span class="fw-bold">{{ $student->nombre_dependencia_receptora }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Responsable</label>
                            <span class="fw-bold">{{ $student->nombre_responsable_dependencia }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Proyecto</label>
                            <span class="fw-bold">{{ $student->proyecto_participa }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Acción de Reactivación --}}
            <div class="mt-4 p-4 bg-white rounded-4 shadow-sm d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold mb-0">¿Este alumno volverá a inscribirse?</h6>
                    <small class="text-muted">Al reactivarlo, regresará al listado de alumnos activos.</small>
                </div>
                <form action="{{ route('bajas.destroy', $student->id_estudiante) }}" method="POST" onsubmit="return confirm('¿Desea reactivar este expediente?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-success rounded-3 px-4">
                        <i class="bi bi-person-check-fill me-1"></i> Reactivar ahora
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .bento-card { border-radius: 15px; }
    .extra-small { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.5px; }
    .bg-light { background-color: #f8f9fa !important; }
    .bg-danger-subtle { background-color: #fceaea !important; }
</style>
@endsection