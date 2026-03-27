@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    {{-- Encabezado --}}
    <div class="row align-items-center mb-4 g-3">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark mb-0">Expediente <span class="text-primary">General</span></h2>
            <p class="text-muted small mb-0">Gestión integral de alumnos y control de estados</p>
        </div>
        <div class="col-md-6 text-md-end">
            <div class="btn-group shadow-sm rounded-4 overflow-hidden border">
                <a href="{{ route('home') }}" class="btn btn-white border-end px-3 py-2">
                    <i class="bi bi-house-door me-1"></i> Panel
                </a>
                <a href="{{ route('students.liberados.list') }}" class="btn btn-light border-end px-3 py-2">
                    <i class="bi bi-patch-check text-success me-1"></i> Ver Liberados
                </a>
                <a href="{{ route('bajas.index') }}" class="btn btn-light px-3 py-2">
                    <i class="bi bi-archive text-danger me-1"></i> Historial de Bajas
                </a>
            </div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="card bento-card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <div class="row g-3 align-items-center">
                <div class="col-md-auto">
                    <span class="text-muted small fw-bold text-uppercase"><i class="bi bi-funnel me-1"></i> Filtrar:</span>
                </div>
                <div class="col-md-4">
                    <select id="filterCarrera" class="form-select border-0 bg-light rounded-3 shadow-sm">
                        <option value="">Todas las Carreras</option>
                        @foreach($students->pluck('perfil_profesional_carrera')->unique() as $carrera)
                            <option value="{{ $carrera }}">{{ $carrera }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select id="filterSemestre" class="form-select border-0 bg-light rounded-3 shadow-sm">
                        <option value="">Semestre (Todos)</option>
                        @foreach(range(1, 12) as $s)
                            <option value="{{ $s }}°">{{ $s }}° Semestre</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select id="filterEstado" class="form-select border-0 bg-light rounded-3 shadow-sm">
                        <option value="">Estado (Todos)</option>
                        <option value="Inscrito">Inscritos</option>
                        <option value="Liberacion">Liberados</option>
                        <option value="Baja">Bajas</option>
                    </select>
                </div>
                <div class="col text-end">
                    <button id="resetFilters" class="btn btn-outline-secondary btn-sm border-0">
                        <i class="bi bi-arrow-counterclockwise"></i> Limpiar Filtros
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de Estudiantes --}}
    <div class="card bento-card border-0 shadow-sm overflow-hidden">
        <div class="p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="mainTable">
                    <thead>
                        <tr class="text-muted small bg-light">
                            <th class="border-0 ps-3">MATRÍCULA</th>
                            <th class="border-0">ESTUDIANTE / CARRERA</th>
                            <th class="border-0 text-center">SEM.</th>
                            <th class="border-0">ESTADO</th>
                            <th class="border-0 text-center pe-3">GESTIÓN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr class="{{ $student->status == 'BAJA' ? 'bg-light opacity-75' : '' }}">
                            <td class="ps-3">
                                <span class="fw-bold {{ $student->status == 'BAJA' ? 'text-muted' : 'text-primary' }}">
                                    {{ $student->num_cuenta }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold mb-0">{{ $student->nombre }} {{ $student->apellido_paterno }}</span>
                                    <small class="text-muted text-truncate" style="max-width: 280px;">{{ $student->perfil_profesional_carrera }}</small>
                                </div>
                            </td>
                            <td class="text-center" data-search="{{ $student->semestre }}°">
                                <span class="badge bg-white text-dark border rounded-3 fw-normal">
                                    {{ $student->semestre }}°
                                </span>
                            </td>
                            <td>
                                @if($student->status == 'BAJA')
                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2 small">Baja</span>
                                @elseif($student->status == 'LIBERACION')
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2 small">Liberado</span>
                                @else
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 small">Inscrito</span>
                                @endif
                            </td>
                            <td class="text-center pe-3">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-white border-0 shadow-sm rounded-3" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-2">
                                        <li>
                                            <a class="dropdown-item rounded-3 py-2" href="{{ route('students.show', $student->id_estudiante) }}">
                                                <i class="bi bi-file-earmark-person text-primary me-2"></i> Ver Detalles
                                            </a>
                                        </li>
                                        {{-- Acción Editar --}}
                                        <li>
                                            <a class="dropdown-item rounded-3 py-2" href="{{ route('students.edit', $student->id_estudiante) }}">
                                                <i class="bi bi-pencil-square text-warning me-2"></i> Editar Datos
                                            </a>
                                        </li>
                                        
                                        @if($student->status != 'BAJA')
                                        <li><hr class="dropdown-divider opacity-50"></li>
                                        <li>
                                            <button type="button" class="dropdown-item rounded-3 py-2 text-danger fw-bold" 
                                                onclick="prepararBaja('{{ $student->id_estudiante }}', '{{ $student->nombre }} {{ $student->apellido_paterno }}')">
                                                <i class="bi bi-person-dash me-2"></i> Registrar Baja
                                            </button>
                                        </li>
                                        @endif

                                        @if($student->status != 'LIBERACION' && $student->status != 'BAJA')
                                        <li>
                                            <button type="button" class="dropdown-item rounded-3 py-2 text-success fw-bold" 
                                                    onclick="prepararLiberacion('{{ $student->id_estudiante }}', '{{ $student->nombre }} {{ $student->apellido_paterno }}')">
                                                <i class="bi bi-patch-check me-2"></i> Marcar Liberado
                                            </button>
                                        </li>
                                        @endif

                                        {{-- Acción Eliminar --}}
                                        <li><hr class="dropdown-divider opacity-50"></li>
                                        <li>
                                            <button type="button" class="dropdown-item rounded-3 py-2 text-danger" 
                                                    onclick="confirmarEliminacion('{{ $student->id_estudiante }}', '{{ $student->nombre }} {{ $student->apellido_paterno }}')">
                                                <i class="bi bi-trash3 me-2"></i> Eliminar Registro
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL ELIMINAR REGISTRO --}}
<div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 text-center">
            <div class="modal-body p-4">
                <i class="bi bi-exclamation-octagon text-danger display-4 mb-3"></i>
                <h5 class="fw-bold">¿Eliminar registro?</h5>
                <p class="text-muted small">Esta acción borrará permanentemente a:<br><strong id="nombreEliminar"></strong></p>
                <form id="formEliminar" method="POST">
                    @csrf @method('DELETE')
                    <div class="d-grid gap-2 mt-3">
                        <button type="submit" class="btn btn-danger rounded-3 fw-bold shadow-sm">Sí, eliminar</button>
                        <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL DE BAJA --}}
<div class="modal fade" id="modalBaja" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-danger text-white border-0 py-3">
                <h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle me-2"></i>Formulario de Baja</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formBaja" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <p class="text-muted small mb-1 text-uppercase">Alumno a dar de baja:</p>
                        <h5 id="nombreAlumnoBaja" class="fw-bold text-dark"></h5>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Fecha de Baja</label>
                        <input type="date" name="fecha_baja" class="form-control border-0 bg-light rounded-3 shadow-sm" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold text-muted small text-uppercase">Motivo de la Baja</label>
                        <textarea name="motivo_baja" class="form-control border-0 bg-light rounded-3 shadow-sm" rows="4" placeholder="Justificación..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger rounded-3 px-4 shadow-sm fw-bold">Confirmar Baja</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL DE LIBERACIÓN --}}
<div class="modal fade" id="modalLiberacion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-success text-white border-0 py-3">
                <h5 class="modal-title fw-bold"><i class="bi bi-patch-check me-2"></i>Modalidad de Liberación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formLiberacion" method="POST">
                @csrf
                <div class="modal-body p-4 text-center">
                    <p class="text-muted small mb-3">Seleccione la modalidad para: <br><strong id="nombreAlumnoLibera" class="text-dark"></strong></p>
                    <div class="d-flex flex-column gap-2 text-start">
                        <div class="form-check p-3 border rounded-3 hover-bg-light">
                            <input class="form-check-input" type="radio" name="modalidad_liberacion" id="mod1" value="CONCLUCION DE SERVICIO SOCIAL" required>
                            <label class="form-check-label fw-semibold" for="mod1">LIBERACION POR CONCLUCION</label>
                        </div>
                        <div class="form-check p-3 border rounded-3 hover-bg-light">
                            <input class="form-check-input" type="radio" name="modalidad_liberacion" id="mod2" value="ARTICULO 21 ESTATAL-91 FEDERAL">
                            <label class="form-check-label fw-semibold" for="mod2">ARTICULO 21 ESTATAL-91 FEDERAL</label>
                        </div>
                        <div class="form-check p-3 border rounded-3 hover-bg-light">
                            <input class="form-check-input" type="radio" name="modalidad_liberacion" id="mod3" value="ARTICULO 19">
                            <label class="form-check-label fw-semibold" for="mod3">ARTICULO 19</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success rounded-3 px-4 shadow-sm fw-bold">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .btn-white { background: #fff; color: #333; }
    .table-hover tbody tr:hover { background-color: rgba(13, 110, 253, 0.02); }
    .bento-card { border-radius: 1.25rem; }
    .hover-bg-light:hover { background-color: #f8f9fa; cursor: pointer; }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        var table = $('#mainTable').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
            pageLength: 25,
            dom: '<"d-flex justify-content-between align-items-center mb-3"f>rtip',
        });

        $('#filterCarrera').on('change', function() { table.column(1).search(this.value).draw(); });
        $('#filterSemestre').on('change', function() { 
            var val = $(this).val();
            table.column(2).search(val ? '^' + val + '$' : '', true, false).draw(); 
        });
        $('#filterEstado').on('change', function() { table.column(3).search(this.value).draw(); });
        $('#resetFilters').on('click', function() {
            $('#filterCarrera, #filterSemestre, #filterEstado').val('');
            table.search('').columns().search('').draw();
        });
    });

    function confirmarEliminacion(id, nombre) {
        $('#nombreEliminar').text(nombre);
        let url = "{{ route('students.destroy', ':id') }}".replace(':id', id);
        $('#formEliminar').attr('action', url);
        $('#modalEliminar').modal('show');
    }

    function prepararBaja(id, nombre) {
        $('#nombreAlumnoBaja').text(nombre);
        $('#formBaja').attr('action', "{{ route('students.baja.manual', ':id') }}".replace(':id', id));
        $('#modalBaja').modal('show');
    }

    function prepararLiberacion(id, nombre) {
        $('#nombreAlumnoLibera').text(nombre);
        $('#formLiberacion').attr('action', "{{ route('students.liberar.manual', ':id') }}".replace(':id', id));
        $('#modalLiberacion').modal('show');
    }
</script>
@endpush
@endsection