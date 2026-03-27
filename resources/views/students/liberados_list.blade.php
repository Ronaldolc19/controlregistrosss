@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0">Padrón de <span class="text-success">Liberados</span></h2>
            <p class="text-muted small">Registros históricos de terminación de servicio social</p>
        </div>
        <a href="{{ route('students.index') }}" class="btn btn-dark rounded-pill shadow-sm">
            <i class="bi bi-people me-1"></i> Ver Estudiantes Activos
        </a>
    </div>

    <div class="card bento-card border-0 shadow-sm p-4 border-top border-success border-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="liberadosTable">
                <thead>
                    <tr class="text-muted small text-uppercase">
                        <th>ID LIBERACIÓN</th> {{-- Nueva columna para ubicar el registro --}}
                        <th>MATRÍCULA</th>
                        <th>NOMBRE DEL ALUMNO</th>
                        <th class="text-center">MODALIDAD</th>
                        <th class="text-center">FECHA</th>
                        <th class="text-center">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($liberados as $registro)
                    <tr>
                        <td class="fw-bold text-muted">#{{ $registro->id_liberado }}</td>
                        <td class="fw-bold text-success">{{ $registro->estudiante->num_cuenta }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $registro->estudiante->nombre }} {{ $registro->estudiante->apellido_paterno }}</div>
                            <small class="text-muted">{{ $registro->estudiante->perfil_profesional_carrera }}</small>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-success-subtle text-success border border-success px-3 py-2">
                                {{ $registro->modalidad }}
                            </span>
                        </td>
                        <td class="text-center fw-semibold">
                            {{ \Carbon\Carbon::parse($registro->fecha_liberacion)->format('d/m/Y') }}
                        </td>
                        <td class="text-center">
                            <a href="{{ route('students.show', $registro->id_estudiante) }}" class="btn btn-sm btn-outline-primary rounded-3">
                                <i class="bi bi-eye"></i> Ver Expediente
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection