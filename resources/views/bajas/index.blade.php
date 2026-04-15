@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- Encabezado con Estilo Bento --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-800 text-danger text-uppercase mb-1" style="letter-spacing: -0.5px;">
                <i class="bi bi-person-x-fill me-2"></i>Alumnos en Baja
            </h2>
            <p class="text-muted small fw-bold mb-0 text-uppercase" style="letter-spacing: 1px;">
                Historial de suspensión de Servicio Social
            </p>
        </div>
        <a href="{{ route('students.index') }}" class="btn btn-light border shadow-sm px-4 py-2 fw-800 text-uppercase extra-small rounded-3 transition-hover">
            <i class="bi bi-arrow-left me-2"></i> Volver a Inscritos
        </a>
    </div>

    {{-- Contenedor de Tabla --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <tr>
                        <th class="ps-4 py-3 fw-800 text-secondary text-uppercase extra-small">Estudiante</th>
                        <th class="py-3 fw-800 text-secondary text-uppercase extra-small text-center">Registro SS</th>
                        <th class="py-3 fw-800 text-secondary text-uppercase extra-small text-center">Fecha de Baja</th>
                        <th class="py-3 fw-800 text-secondary text-uppercase extra-small">Motivo</th>
                        <th class="text-end pe-4 py-3 fw-800 text-secondary text-uppercase extra-small">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bajas as $baja)
                    <tr class="bg-white border-bottom shadow-hover">
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                                     style="width: 45px; height: 45px; background-color: #fef2f2; color: #dc2626; border: 1px solid #fee2e2;">
                                    <i class="bi bi-person-slash fs-5"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0 fw-800 text-dark">{{ $baja->estudiante->nombre }} {{ $baja->estudiante->apellido_paterno }}</h6>
                                    <span class="badge bg-danger-subtle text-danger extra-small fw-800 px-2 py-1 rounded-2">
                                        {{ $baja->estudiante->num_cuenta }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="fw-bold text-dark small p-2 rounded-3 border bg-light">
                                {{ $baja->registro_estatal_ss }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex flex-column">
                                <span class="fw-800 text-dark small">{{ \Carbon\Carbon::parse($baja->fecha_baja)->format('d/m/Y') }}</span>
                                <small class="text-muted extra-small uppercase fw-bold">Calendario Oficial</small>
                            </div>
                        </td>
                        <td>
                            <div class="p-2 rounded-3 bg-light border-start border-danger border-4">
                                <span class="d-inline-block text-truncate fw-bold text-secondary small" style="max-width: 220px;" title="{{ $baja->motivo_baja }}">
                                    {{ $baja->motivo_baja }}
                                </span>
                            </div>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                {{-- 1. Ver Detalle --}}
                                <a href="{{ route('bajas.show', $baja->id_estudiante) }}" class="btn-action bg-info-subtle text-info" title="Ver Expediente">
                                    <i class="bi bi-eye-fill"></i>
                                </a>

                                {{-- 2. Editar --}}
                                <a href="{{ route('bajas.edit', $baja->id_estudiante) }}" class="btn-action bg-warning-subtle text-warning" title="Editar Información">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                {{-- 3. Reactivar --}}
                                <form action="{{ route('bajas.destroy', $baja->id_estudiante) }}" method="POST" class="reactivar-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-action bg-success-subtle text-success btn-reactivar" title="Reactivar Estudiante">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </button>
                                </form>

                                {{-- 4. Eliminar Permanente --}}
                                <form action="{{ route('students.destroy', $baja->id_estudiante) }}" method="POST" class="eliminar-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-action bg-danger-subtle text-danger btn-eliminar" title="Borrar Permanente">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="py-4">
                                <i class="bi bi-clipboard-x display-1 text-muted opacity-25"></i>
                                <h5 class="text-muted mt-3 fw-800 text-uppercase">Sin registros de baja</h5>
                                <p class="small text-muted fw-bold">No se han encontrado alumnos con suspensión de servicio.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Estilos Premium --}}
<style>
    .fw-800 { font-weight: 800; }
    .extra-small { font-size: 0.7rem; letter-spacing: 0.5px; }
    .transition-hover { transition: all 0.2s ease; }
    .transition-hover:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important; }

    .btn-action {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        border: none;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-action:hover {
        transform: scale(1.1);
        filter: brightness(0.95);
    }

    .shadow-hover:hover {
        background-color: #fcfcfc !important;
        transition: background 0.3s ease;
    }

    /* Colores sutiles para botones de acción */
    .bg-info-subtle { background-color: #e0f2fe; }
    .bg-warning-subtle { background-color: #fef3c7; }
    .bg-success-subtle { background-color: #dcfce7; }
    .bg-danger-subtle { background-color: #fee2e2; }
</style>

{{-- Scripts de Alerta (SweetAlert2) --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Alerta para Reactivación
        document.querySelectorAll('.btn-reactivar').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('.reactivar-form');
                Swal.fire({
                    title: '¿Reactivar estudiante?',
                    text: "El alumno volverá al listado de inscritos activos.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#007f3f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="bi bi-check-lg"></i> Sí, reactivar',
                    cancelButtonText: 'Cancelar',
                    customClass: { popup: 'rounded-4' }
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });

        // Alerta para Eliminación Permanente (Estilo Crítico)
        document.querySelectorAll('.btn-eliminar').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('.eliminar-form');
                Swal.fire({
                    title: '¿ELIMINAR PERMANENTEMENTE?',
                    text: "Esta acción borrará todo el historial del alumno y no se puede deshacer.",
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="bi bi-trash-fill"></i> Eliminar para siempre',
                    cancelButtonText: 'Conservar registro',
                    customClass: { popup: 'rounded-4' }
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    });
</script>
@endsection