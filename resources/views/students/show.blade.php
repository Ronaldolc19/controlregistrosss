@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Header con Breadcrumbs y Navegación --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none fw-bold text-muted">Expedientes</a></li>
                    <li class="breadcrumb-item active fw-bold" style="color: var(--tesvb-green)">Ficha Técnica</li>
                </ol>
            </nav>
            <h2 class="fw-800 text-dark mb-0">
                <i class="bi bi-person-badge me-2" style="color: var(--tesvb-green)"></i>Matrícula: <span style="color: var(--tesvb-green)">{{ $student->num_cuenta }}</span>
            </h2>
        </div>
        <div>
            <a href="{{ route('students.index') }}" class="btn btn-light border-0 px-4 py-2 fw-800 rounded-3 shadow-sm">
                <i class="bi bi-arrow-left me-2"></i>Regresar al Listado
            </a>
        </div>
    </div>

    <div class="row g-4">
        {{-- Columna Izquierda: Perfil Rápido --}}
        <div class="col-xl-4 col-lg-5">
            <div class="card card-premium border-0 shadow-sm p-4 text-center mb-4">
                <div class="mx-auto mb-3 avatar-placeholder d-flex align-items-center justify-content-center">
                    <span class="display-4 fw-800">{{ substr($student->nombre, 0, 1) }}</span>
                </div>
                <h4 class="fw-800 mb-0 text-uppercase text-dark">{{ $student->nombre }}</h4>
                <h5 class="fw-bold text-muted mb-3">{{ $student->apellido_paterno }} {{ $student->apellido_materno }}</h5>
                
                <div class="mb-4">
                    @php
                        $statusStyles = [
                            'BAJA' => 'status-baja',
                            'LIBERACION' => 'status-liberado',
                            'INSCRIPCION' => 'status-inscrito'
                        ][$student->status] ?? 'bg-secondary text-white';
                    @endphp
                    <span class="status-pill {{ $statusStyles }} px-4 py-2">
                        <i class="bi bi-circle-fill me-2 small"></i>{{ $student->status }}
                    </span>
                </div>
                
                <div class="row g-2 border-top pt-4 mt-2">
                    <div class="col-6 border-end">
                        <small class="d-block text-muted fw-800 extra-small mb-1 uppercase">Promedio</small>
                        <span class="fw-800 fs-4 text-dark">{{ $student->promedio }}</span>
                    </div>
                    <div class="col-6">
                        <small class="d-block text-muted fw-800 extra-small mb-1 uppercase">Avance Plan</small>
                        <span class="fw-800 fs-4" style="color: var(--tesvb-green)">{{ $student->porcentaje_cubierto_plan }}%</span>
                    </div>
                </div>
            </div>

            <div class="card card-premium border-0 shadow-sm p-4">
                <h6 class="fw-800 mb-4 text-uppercase text-muted extra-small border-bottom pb-3">
                    <i class="bi bi-person-lines-fill me-2"></i>Información Personal
                </h6>
                <div class="row g-4">
                    <div class="col-6">
                        <label class="text-muted extra-small d-block mb-1">SEXO</label>
                        <span class="fw-bold text-dark"><i class="bi bi-gender-ambiguous me-1"></i> {{ $student->sexo }}</span>
                    </div>
                    <div class="col-6">
                        <label class="text-muted extra-small d-block mb-1">EDAD</label>
                        <span class="fw-bold text-dark">{{ $student->edad }} años</span>
                    </div>
                    <div class="col-12">
                        <label class="text-muted extra-small d-block mb-1">LENGUA INDÍGENA</label>
                        <span class="fw-bold {{ $student->habla_lengua_indigena == 'SI' ? 'text-success' : 'text-dark' }}">
                            {{ $student->habla_lengua_indigena }} 
                            @if($student->cual_lengua && $student->cual_lengua != 'N/A') <span class="badge bg-light text-dark fw-normal border ms-1">{{ $student->cual_lengua }}</span> @endif
                        </span>
                    </div>
                    <div class="col-12">
                        <label class="text-muted extra-small d-block mb-1">DISCAPACIDAD</label>
                        <span class="fw-bold {{ $student->tiene_discapacidad == 'SI' ? 'text-danger' : 'text-dark' }}">
                            {{ $student->tiene_discapacidad }}
                            @if($student->cual_discapacidad && $student->cual_discapacidad != 'N/A') <span class="badge bg-danger-subtle text-danger fw-normal ms-1">{{ $student->cual_discapacidad }}</span> @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Columna Derecha: Detalles Académicos y Dependencia --}}
        <div class="col-xl-8 col-lg-7">
            {{-- Institución --}}
            <div class="card card-premium border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-800 mb-4 d-flex align-items-center" style="color: var(--tesvb-green)">
                        <i class="bi bi-bank2 me-2"></i> Institución Educativa
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="text-muted small d-block mb-1">Nombre de la Escuela</label>
                            <span class="fw-800 text-dark fs-6 text-uppercase">{{ $student->nombre_escuela ?? 'CENTRO UNIVERSITARIO VALLE DE BRAVO' }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block mb-1">Clave CCT</label>
                            <span class="fw-bold text-dark px-2 py-1 bg-light rounded border">{{ $student->clave_cct }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block mb-1">Subsistema</label>
                            <span class="fw-bold text-dark">{{ $student->subsistema }}</span>
                        </div>
                        <div class="col-md-12">
                            <label class="text-muted small d-block mb-1">Ubicación del Plantel</label>
                            <span class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $student->direccion_escuela }}, {{ $student->municipio_escuela }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Trayectoria --}}
            <div class="card card-premium border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="row align-items-center mb-4">
                        <div class="col">
                            <h5 class="fw-800 mb-0 d-flex align-items-center" style="color: var(--tesvb-green)">
                                <i class="bi bi-mortarboard-fill me-2"></i> Trayectoria Académica
                            </h5>
                        </div>
                        <div class="col-auto text-end">
                            <span class="badge bg-dark px-3 py-2 fw-800 rounded-3">SEMESTRE: {{ $student->semestre }}°</span>
                        </div>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-8">
                            <label class="text-muted small d-block mb-1 text-uppercase fw-800">Carrera / Perfil Profesional</label>
                            <span class="fw-800 fs-5 text-dark">{{ $student->perfil_profesional_carrera }}</span>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small d-block mb-1 text-uppercase fw-800">Registro Estatal S.S.</label>
                            <span class="fw-bold text-dark bg-light p-2 rounded d-inline-block">{{ $student->registro_estatal_ss ?? 'PENDIENTE' }}</span>
                        </div>
                        <div class="col-md-12">
                            <div class="p-3 rounded-4 border-start border-4 shadow-sm" style="background: rgba(0,127,63,0.03); border-color: var(--tesvb-green) !important;">
                                <label class="text-muted small d-block text-uppercase fw-800 mb-1">Periodo de Servicio Social</label>
                                <span class="fw-800 fs-6 text-dark">
                                    <i class="bi bi-calendar-event me-2"></i>{{ $student->periodo_inicio }} 
                                    <span class="mx-3 text-muted">→</span> 
                                    {{ $student->periodo_termino }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Dependencia --}}
            <div class="card card-premium border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-800 mb-4 d-flex align-items-center" style="color: var(--tesvb-green)">
                        <i class="bi bi-building-check me-2"></i> Dependencia Receptora
                    </h5>
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="text-muted small d-block mb-1">Nombre de la Dependencia</label>
                            <span class="fw-800 text-dark text-uppercase fs-6">{{ $student->nombre_dependencia_receptora }}</span>
                        </div>
                        <div class="col-md-12">
                            <label class="text-muted small d-block mb-1">Proyecto Asignado</label>
                            <div class="bg-light p-3 rounded-3 border-0">
                                <span class="text-dark fw-bold"><i class="bi bi-briefcase me-2"></i>{{ $student->proyecto_participa }}</span>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <label class="text-muted small d-block mb-1">Responsable Directo</label>
                            <span class="fw-800 text-dark"><i class="bi bi-person-check me-2"></i>{{ $student->nombre_responsable_dependencia }}</span>
                        </div>
                        <div class="col-md-5">
                            <label class="text-muted small d-block mb-1">Sector Institucional</label>
                            <span class="badge bg-secondary text-white px-3 py-2 fw-800 rounded-pill uppercase small">{{ $student->sector }}</span>
                        </div>
                        
                        <div class="col-12 mt-2"><hr class="opacity-10"></div>

                        <div class="col-md-4">
                            <label class="text-muted small d-block mb-1">Apoyo Económico</label>
                            <span class="fw-800 {{ str_contains($student->ss_con_o_sin_beca, 'CON') ? 'text-success' : 'text-muted' }}">
                                <i class="bi bi-cash-stack me-1"></i>{{ $student->ss_con_o_sin_beca }}
                            </span>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small d-block mb-1">Monto Estímulo</label>
                            <span class="fw-800 text-dark">{{ $student->monto_estimulo ?? '$0.00' }}</span>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small d-block mb-1">Horario Asignado</label>
                            <span class="fw-800 text-dark" style="color: var(--tesvb-green) !important;">
                                <i class="bi bi-clock-history me-1"></i>{{ $student->horario_servicio }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .fw-800 { font-weight: 800; }
    .extra-small { 
        font-size: 0.65rem; 
        letter-spacing: 1px; 
        font-weight: 900; 
        text-transform: uppercase;
    }
    
    /* Placeholder del avatar con el color de la marca */
    .avatar-placeholder {
        width: 110px;
        height: 110px;
        background: rgba(0, 127, 63, 0.1);
        color: var(--tesvb-green);
        border-radius: 25px;
        border: 2px solid rgba(0, 127, 63, 0.2);
    }

    /* Píldoras de Estado unificadas */
    .status-pill {
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
    }
    .status-inscrito { background: rgba(0, 127, 63, 0.15); color: var(--tesvb-green); }
    .status-liberado { background: rgba(13, 110, 253, 0.15); color: #0d6efd; }
    .status-baja { background: rgba(220, 53, 69, 0.15); color: #dc3545; }

    /* Tarjetas Premium */
    .card-premium {
        border-radius: 20px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card-premium:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.08) !important;
    }

    .breadcrumb-item + .breadcrumb-item::before {
        content: "→";
        color: #cbd5e1;
    }
</style>
@endpush
@endsection