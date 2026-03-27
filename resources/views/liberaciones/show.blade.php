@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    <li class="breadcrumb-item"><a href="{{ route('liberaciones.index') }}" class="text-decoration-none text-success">Liberaciones</a></li>
                    <li class="breadcrumb-item active">Ficha Técnica de Liberación</li>
                </ol>
            </nav>
            <h2 class="fw-bold text-dark mb-0">
                <i class="bi bi-patch-check-fill me-2 text-success"></i>Expediente Liberado: <span class="text-success">{{ $student->num_cuenta }}</span>
            </h2>
        </div>
        <div>
            <a href="{{ route('liberaciones.index') }}" class="btn btn-dark rounded-3 px-4 shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Regresar al Listado
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-4 col-lg-5">
            {{-- Card de Perfil --}}
            <div class="card bento-card border-0 shadow-sm p-4 text-center mb-4 border-top border-success border-4">
                <div class="mx-auto mb-3 bg-success-subtle rounded-circle d-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                    <i class="bi bi-mortarboard-fill text-success" style="font-size: 4rem;"></i>
                </div>
                <h4 class="fw-bold mb-1 text-uppercase">{{ $student->nombre }}</h4>
                <h5 class="fw-bold text-muted mb-3">{{ $student->apellido_paterno }} {{ $student->apellido_materno }}</h5>
                
                <div class="d-flex justify-content-center mb-4">
                    <span class="badge bg-success rounded-pill px-4 py-2 shadow-sm">ESTADO: LIBERADO</span>
                </div>
                
                {{-- Sección de Datos de Liberación --}}
                <div class="text-start border-top pt-3">
                    <p class="small text-muted text-uppercase fw-bold mb-2 text-success">Detalles de Finalización</p>
                    <div class="p-3 bg-light rounded-3 border-start border-success border-4 mb-3">
                        <div class="mb-2">
                            <label class="text-muted extra-small d-block text-uppercase">Modalidad</label>
                            <span class="fw-bold text-dark">{{ $liberacion->modalidad }}</span>
                        </div>
                        <div class="mb-0">
                            <label class="text-muted extra-small d-block text-uppercase">Fecha de Liberación</label>
                            <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($liberacion->fecha_liberacion)->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    
                    <p class="small text-muted text-uppercase fw-bold mb-2">Resumen Académico</p>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span>Semestre:</span> <span class="fw-bold">{{ $student->semestre }}</span>
                    </div>
                    <div class="d-flex justify-content-between small">
                        <span>Avance Plan:</span> <span class="fw-bold text-success">100% Completado</span>
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
                    <h5 class="fw-bold mb-4 text-success"><i class="bi bi-award me-2"></i>Trayectoria y Registro</h5>
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="text-muted small d-block">Programa Educativo / Carrera</label>
                            <div class="p-3 bg-light rounded-3 fw-bold border-start border-success border-4">
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
                            <label class="text-muted small d-block">Periodo de Servicio</label>
                            <span class="fw-bold text-dark">{{ $student->periodo_inicio }} - {{ $student->periodo_termino }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Subsistema</label>
                            <span class="fw-bold">{{ $student->subsistema }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Dependencia Receptora --}}
            <div class="card bento-card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 text-success"><i class="bi bi-building-check me-2"></i>Dependencia de Cumplimiento</h5>
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="text-muted small d-block">Nombre de la Dependencia</label>
                            <span class="fw-bold text-dark">{{ $student->nombre_dependencia_receptora }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Responsable</label>
                            <span class="fw-bold">{{ $student->nombre_responsable_dependencia }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Proyecto Realizado</label>
                            <span class="fw-bold">{{ $student->proyecto_participa }}</span>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small d-block">Sector</label>
                            <span class="fw-bold">{{ $student->sector }}</span>
                        </div>
                        <div class="col-md-8">
                            <label class="text-muted small d-block">Horario que cumplió</label>
                            <span class="fw-bold small">{{ $student->horario_servicio }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Botones de Acción Rápida --}}
            <div class="mt-4 d-flex gap-2">
                <a href="{{ route('liberaciones.edit', $student->id_estudiante) }}" class="btn btn-warning rounded-3 px-4 fw-bold">
                    <i class="bi bi-pencil-square me-1"></i> Corregir Datos de Liberación
                </a>
                
            </div>
        </div>
    </div>
</div>

<style>
    .bento-card { border-radius: 15px; }
    .extra-small { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.5px; }
    .bg-light { background-color: #f8f9fa !important; }
    .bg-success-subtle { background-color: #e8f5e9 !important; }
    
    @media print {
        .btn, nav, .breadcrumb { display: none !important; }
        .card { border: 1px solid #ddd !important; shadow: none !important; }
    }
</style>
@endsection