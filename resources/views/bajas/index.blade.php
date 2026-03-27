@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-danger"><i class="bi bi-person-x-fill me-2"></i>Alumnos en Baja</h2>
            <p class="text-muted">Historial de alumnos que han suspendido su Servicio Social.</p>
        </div>
        <a href="{{ route('students.index') }}" class="btn btn-outline-secondary rounded-3">
            <i class="bi bi-arrow-left me-1"></i> Volver a Inscritos
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Estudiante</th>
                        <th>Registro SS</th>
                        <th>Fecha de Baja</th>
                        <th>Motivo</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bajas as $baja)
                    <tr class="bg-white">
                        <td class="ps-4">
                            <div class="d-flex align-items-center py-1">
                                <div class="avatar-sm bg-danger-subtle text-danger rounded-circle p-2 me-3" style="width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                                    <i class="bi bi-person-slash"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark">{{ $baja->estudiante->nombre }} {{ $baja->estudiante->apellido_paterno }}</h6>
                                    <small class="text-muted fw-bold">{{ $baja->estudiante->num_cuenta }}</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-dark border fw-medium px-3 py-2 rounded-pill">{{ $baja->registro_estatal_ss }}</span></td>
                        <td class="text-muted"><i class="bi bi-calendar-x me-1"></i> {{ \Carbon\Carbon::parse($baja->fecha_baja)->format('d/m/Y') }}</td>
                        <td>
                            <span class="d-inline-block text-truncate fw-medium text-secondary" style="max-width: 180px;" title="{{ $baja->motivo_baja }}">
                                {{ $baja->motivo_baja }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            {{-- GRUPO DE ACCIONES --}}
                            <div class="d-flex justify-content-end gap-1">
                                
                                {{-- 1. Ver Detalle (Ojo) --}}
                                <a href="{{ route('bajas.show', $baja->id_estudiante) }}" class="btn btn-sm btn-white border-0 rounded-3 text-info" title="Ver Expediente">
                                    <i class="bi bi-eye-fill fs-6"></i>
                                </a>

                                {{-- 2. Editar Datos (Lápiz) --}}
                                <a href="{{ route('bajas.edit', $baja->id_estudiante) }}" class="btn btn-sm btn-white border-0 rounded-3 text-warning" title="Editar Información">
                                    <i class="bi bi-pencil-square fs-6"></i>
                                </a>

                                {{-- 3. Reactivar (check-fill) --}}
                                <form action="{{ route('bajas.destroy', $baja->id_estudiante) }}" method="POST" onsubmit="return confirm('¿Confirmar reactivación de este estudiante?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-white border-0 rounded-3 text-success fw-bold" title="Reactivar">
                                        <i class="bi bi-check-circle-fill fs-6"></i>
                                    </button>
                                </form>

                                {{-- 4. Eliminar Permanente (trash3) --}}
                                <form action="{{ route('students.destroy', $baja->id_estudiante) }}" method="POST" onsubmit="return confirm('¿ELIMINAR PERMANENTEMENTE? Esta acción no se puede deshacer.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-white border-0 rounded-3 text-danger" title="Eliminar definitivamente">
                                        <i class="bi bi-trash3 fs-6"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="bi bi-clipboard-x display-3 text-muted opacity-25"></i>
                            <p class="text-muted mt-3 fw-bold">No hay alumnos registrados con baja actualmente.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection