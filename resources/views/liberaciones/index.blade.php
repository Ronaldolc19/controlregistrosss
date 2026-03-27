@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-success"><i class="bi bi-patch-check-fill me-2"></i>Servicios Liberados</h2>
            <p class="text-muted small mb-0">Listado oficial de alumnos que han concluido satisfactoriamente.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('students.index') }}" class="btn btn-outline-dark rounded-3 px-3 shadow-sm">
                <i class="bi bi-people me-1"></i> Ver Inscritos
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Estudiante</th>
                        <th>Modalidad</th>
                        <th>Fecha de Liberación</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($liberados as $liberado)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center py-2">
                                <div class="avatar-sm bg-success-subtle text-success rounded-circle p-2 me-3" style="width: 42px; height: 42px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-mortarboard-fill"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark">{{ $liberado->estudiante->nombre }} {{ $liberado->estudiante->apellido_paterno }}</h6>
                                    <small class="text-muted fw-bold">{{ $liberado->estudiante->num_cuenta }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-bold">
                                <i class="bi bi-journal-check me-1"></i> {{ $liberado->modalidad }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($liberado->fecha_liberacion)->format('d/m/Y') }}</span>
                                <small class="text-muted extra-small text-uppercase">Finalizado</small>
                            </div>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-1">
                                {{-- Ver Detalle --}}
                                <a href="{{ route('liberaciones.show', $liberado->id_estudiante) }}" class="btn btn-sm btn-white border-0 text-primary" title="Ver Expediente">
                                    <i class="bi bi-eye-fill fs-5"></i>
                                </a>

                                {{-- Editar --}}
                                <a href="{{ route('liberaciones.edit', $liberado->id_estudiante) }}" class="btn btn-sm btn-white border-0 text-warning" title="Editar Liberación">
                                    <i class="bi bi-pencil-square fs-5"></i>
                                </a>

                                {{-- Anular/Eliminar --}}
                                <form action="{{ route('liberaciones.destroy', $liberado->id_estudiante) }}" method="POST" onsubmit="return confirm('¿Desea anular esta liberación? El alumno volverá al listado de inscritos.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-white border-0 text-danger" title="Anular Liberación">
                                        <i class="bi bi-trash3-fill fs-5"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <div class="py-4">
                                <i class="bi bi-archive display-1 text-muted opacity-25"></i>
                                <p class="text-muted mt-3 fw-bold">No se encontraron registros de liberación.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .avatar-sm { font-size: 1.1rem; }
    .btn-white:hover { background-color: #f8f9fa; transform: translateY(-1px); }
    .extra-small { font-size: 0.65rem; letter-spacing: 0.5px; }
    .bg-success-subtle { background-color: #e8f5e9 !important; }
</style>
@endsection