@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0">Archivo Histórico de <span class="text-danger">Bajas</span></h2>
            <p class="text-muted small">Registros de alumnos que causaron baja del programa</p>
        </div>
        <a href="{{ route('students.index') }}" class="btn btn-dark rounded-pill shadow-sm">
            <i class="bi bi-people me-1"></i> Ver Estudiantes Activos
        </a>
    </div>

    {{-- Tabla de Bajas --}}
    <div class="card bento-card border-0 shadow-sm p-4 border-top border-danger border-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="bajasTable">
                <thead>
                    <tr class="text-muted small text-uppercase">
                        <th class="border-0">Matrícula</th>
                        <th class="border-0">Nombre Completo</th>
                        <th class="border-0">Reg. Estatal S.S.</th>
                        <th class="border-0">Fecha de Baja</th>
                        <th class="border-0">Motivo</th>
                        <th class="border-0 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bajas as $baja)
                    <tr>
                        <td class="fw-bold text-danger">{{ $baja->estudiante->num_cuenta }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $baja->estudiante->nombre }} {{ $baja->estudiante->apellido_paterno }}</div>
                            <small class="text-muted">{{ $baja->estudiante->perfil_profesional_carrera }}</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border fw-normal">
                                {{ $baja->registro_estatal_ss ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="fw-semibold text-secondary">
                            {{ \Carbon\Carbon::parse($baja->fecha_baja)->format('d/m/Y') }}
                        </td>
                        <td>
                            <div class="small text-muted p-2 bg-light rounded shadow-sm" style="max-width: 250px; border-left: 3px solid #dc3545;">
                                {{ $baja->motivo_baja }}
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                {{-- Ver Expediente --}}
                                <a href="{{ route('students.show', $baja->id_estudiante) }}" 
                                   class="btn btn-sm btn-outline-primary rounded-3 shadow-sm" 
                                   title="Ver detalles">
                                    <i class="bi bi-eye"></i>
                                </a>

                                {{-- Botón de Re-incorporar (Alta nuevamente) --}}
                                <form action="{{ route('students.reactivar', $baja->id_estudiante) }}" method="POST" 
                                      onsubmit="return confirm('¿Desea reactivar el expediente y dar de alta nuevamente a este alumno?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success rounded-3 shadow-sm px-3" title="Dar de Alta">
                                        <i class="bi bi-person-check-fill me-1"></i> Re-Alta
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('styles')
<style>
    .bento-card { border-radius: 1rem; }
    .table thead th { font-weight: 700; letter-spacing: 0.5px; }
    .btn-success { background-color: #198754; border: none; }
    .btn-success:hover { background-color: #157347; }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        $('#bajasTable').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
            responsive: true,
            order: [[3, 'desc']] // Ordenar por fecha de baja más reciente por defecto
        });
    });
</script>
@endpush
@endsection