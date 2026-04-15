@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-800 text-primary text-uppercase mb-1">
                <i class="bi bi-file-earmark-check-fill me-2"></i>Control de Constancias
            </h2>
            <p class="text-muted small fw-bold mb-0 text-uppercase">Gestión de documentos oficiales</p>
        </div>
        <a href="{{ route('liberaciones.index') }}" class="btn btn-light border shadow-sm px-4 py-2 fw-800 extra-small rounded-3">
            <i class="bi bi-arrow-left me-2"></i> Volver a Liberados
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <a href="{{ url()->current() }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 {{ !request('estado') ? 'bg-primary text-white' : 'bg-white' }} transition-hover">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="avatar-sm rounded-circle {{ !request('estado') ? 'bg-white text-primary' : 'bg-primary-subtle text-primary' }} d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                            <i class="bi bi-collection-fill fs-5"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-800 extra-small text-uppercase {{ !request('estado') ? 'text-white' : 'text-secondary' }}">Todas</h6>
                            <span class="h5 mb-0 fw-bold">{{ $constancias->count() }}</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ url()->current() . '?estado=pendiente' }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 {{ request('estado') == 'pendiente' ? 'bg-warning text-dark' : 'bg-white' }} transition-hover">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="avatar-sm rounded-circle {{ request('estado') == 'pendiente' ? 'bg-white text-warning' : 'bg-warning-subtle text-warning' }} d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                            <i class="bi bi-clock-history fs-5"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-800 extra-small text-uppercase {{ request('estado') == 'pendiente' ? 'text-dark' : 'text-secondary' }}">Pendientes</h6>
                            <span class="h5 mb-0 fw-bold">{{ $constancias->where('estado', 'pendiente')->count() }}</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ url()->current() . '?estado=emitida' }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 {{ request('estado') == 'emitida' ? 'bg-success text-white' : 'bg-white' }} transition-hover">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="avatar-sm rounded-circle {{ request('estado') == 'emitida' ? 'bg-white text-success' : 'bg-success-subtle text-success' }} d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                            <i class="bi bi-check-circle-fill fs-5"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-800 extra-small text-uppercase {{ request('estado') == 'emitida' ? 'text-white' : 'text-secondary' }}">Emitidas</h6>
                            <span class="h5 mb-0 fw-bold">{{ $constancias->where('estado', 'emitida')->count() }}</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <tr>
                        <th class="ps-4 py-3 fw-800 text-secondary text-uppercase extra-small">Estudiante</th>
                        <th class="py-3 fw-800 text-secondary text-uppercase extra-small text-center">Estado de Documento</th>
                        <th class="text-end pe-4 py-3 fw-800 text-secondary text-uppercase extra-small">Acciones Disponibles</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Filtrado visual para no afectar los contadores de las cards
                        $filtradas = $constancias;
                        if(request('estado')) {
                            $filtradas = $constancias->where('estado', request('estado'));
                        }
                    @endphp

                    @forelse($filtradas as $constancia)
                    <tr class="bg-white border-bottom shadow-hover">
                        <td class="ps-4">
                            <div class="d-flex align-items-center py-2">
                                <div class="avatar-sm rounded-circle d-flex align-items-center justify-content-center bg-primary-subtle text-primary" style="width: 40px; height: 40px;">
                                    <i class="bi bi-person-badge-fill"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0 fw-800 text-dark text-uppercase small">
                                        {{ $constancia->estudiante->nombre }} {{ $constancia->estudiante->apellido_paterno }}
                                    </h6>
                                    <small class="text-muted fw-bold">{{ $constancia->estudiante->num_cuenta }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            @if($constancia->estado == 'emitida')
                                <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle fw-800 px-3 py-2 extra-small">
                                    <i class="bi bi-check-circle-fill me-1"></i> EMITIDA
                                </span>
                            @else
                                <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle fw-800 px-3 py-2 extra-small">
                                    <i class="bi bi-clock-history me-1"></i> PENDIENTE
                                </span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                @if($constancia->estado == 'emitida' && $constancia->pdf_path)
                                    <a href="{{ asset($constancia->pdf_path) }}" target="_blank" class="btn btn-sm btn-danger rounded-3 px-3 fw-800 extra-small shadow-sm">
                                        <i class="bi bi-file-pdf-fill me-1"></i> PDF
                                    </a>
                                @endif
                                
                                <a href="{{ route('constancia.generar', $constancia->id_estudiante) }}" 
                                   class="btn {{ $constancia->estado == 'emitida' ? 'btn-outline-primary' : 'btn-success' }} btn-sm rounded-3 px-3 fw-800 extra-small text-uppercase shadow-sm">
                                    <i class="bi {{ $constancia->estado == 'emitida' ? 'bi-arrow-clockwise' : 'bi-gear-fill' }} me-1"></i>
                                    {{ $constancia->estado == 'emitida' ? 'Regenerar' : 'Generar' }}
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-5 text-muted">
                            <i class="bi bi-search display-4 opacity-25"></i>
                            <p class="mt-2 fw-bold text-uppercase small">Sin registros con el filtro seleccionado</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .fw-800 { font-weight: 800; }
    .extra-small { font-size: 0.7rem; }
    .bg-primary-subtle { background-color: #eef2ff !important; }
    .bg-success-subtle { background-color: #f0fdf4 !important; }
    .bg-warning-subtle { background-color: #fffbeb !important; }
    .shadow-hover:hover { background-color: #fcfcfc !important; }
    .transition-hover { transition: all 0.2s ease-in-out; }
    .transition-hover:hover { transform: translateY(-3px); }
</style>
@endsection