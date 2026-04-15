@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    {{-- Encabezado Estilo Premium --}}
    <div class="row align-items-center mb-4 g-3">
        <div class="col-md-6">
            <h2 class="fw-800 text-dark mb-0">Expediente <span style="color: var(--tesvb-green)">General</span></h2>
            <p class="text-muted small mb-0">Gestión integral de alumnos y control de estados de servicio social</p>
        </div>
        <div class="col-md-6 text-md-end">
            <div class="d-inline-flex gap-2 bg-white p-2 rounded-4 shadow-sm border">
                <a href="{{ route('students.create') }}" class="btn btn-tesvb py-2 px-3">
                    <i class="bi bi-person-plus-fill me-1"></i> Nuevo Registro
                </a>
                
                {{-- CAMBIO SOLICITADO: RUTA ACTUALIZADA A students.liberados --}}
                <a href="{{ route('liberaciones.index') }}" class="btn btn-light border-0 px-3 py-2 fw-bold text-dark rounded-3">
                    <i class="bi bi-patch-check text-success me-1"></i> Ver Liberados
                </a>

                <a href="{{ route('bajas.index') }}" class="btn btn-light border-0 px-3 py-2 fw-bold text-dark rounded-3">
                    <i class="bi bi-archive text-danger me-1"></i> Historial de Bajas
                </a>
            </div>
        </div>
    </div>

    {{-- Filtros Dinámicos Estilo Bento --}}
    <div class="card card-premium border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-12 mb-2">
                    <h6 class="fw-800 text-muted uppercase extra-small mb-0">
                        <i class="bi bi-funnel-fill me-1"></i> Panel de Filtros
                    </h6>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-800 text-muted text-uppercase">Carrera</label>
                    <select id="filterCarrera" class="form-select border-0 bg-light rounded-3 shadow-none fw-bold text-dark">
                        <option value="">Todas las Carreras</option>
                        @foreach($students->pluck('perfil_profesional_carrera')->unique()->sort() as $carrera)
                            <option value="{{ $carrera }}">{{ $carrera }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-800 text-muted text-uppercase">Semestre</label>
                    <select id="filterSemestre" class="form-select border-0 bg-light rounded-3 shadow-none fw-bold text-dark">
                        <option value="">Todos</option>
                        @foreach($students->pluck('semestre')->unique()->sort() as $semestre)
                            <option value="{{ $semestre }}°">{{ $semestre }}° Semestre</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-800 text-muted text-uppercase">Estado</label>
                    <select id="filterEstado" class="form-select border-0 bg-light rounded-3 shadow-none fw-bold text-dark">
                        <option value="">Todos</option>
                        <option value="Inscrito">Inscritos</option>
                        <option value="Liberado">Liberados</option>
                        <option value="Baja">Bajas</option>
                    </select>
                </div>
                <div class="col text-end">
                    <button id="resetFilters" class="btn btn-light fw-bold text-muted border-0 rounded-3 px-4">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Limpiar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de Estudiantes Estilo Bento/Premium --}}
    <div class="card card-premium border-0 shadow-sm overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="mainTable">
                    <thead>
                        <tr>
                            <th class="border-0 ps-4 py-3 text-muted extra-small fw-800 uppercase">Matrícula</th>
                            <th class="border-0 py-3 text-muted extra-small fw-800 uppercase">Estudiante / Carrera</th>
                            <th class="border-0 text-center py-3 text-muted extra-small fw-800 uppercase">Sem.</th>
                            <th class="border-0 py-3 text-muted extra-small fw-800 uppercase">Estado</th>
                            <th class="border-0 text-center pe-4 py-3 text-muted extra-small fw-800 uppercase">Gestión</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr class="{{ $student->status == 'BAJA' ? 'opacity-50 grayscale' : '' }}">
                            <td class="ps-4">
                                <span class="fw-800 text-dark fs-6">{{ $student->num_cuenta }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-letter me-3 d-flex align-items-center justify-content-center fw-800 fs-5 uppercase rounded-3" style="width: 40px; height: 40px; background: rgba(0, 127, 63, 0.1); color: var(--tesvb-green); border: 1px solid rgba(0, 127, 63, 0.2);">
                                        {{ substr($student->nombre, 0, 1) }}
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-dark mb-0 fs-6">{{ $student->nombre }} {{ $student->apellido_paterno }}</span>
                                        <small class="text-muted text-truncate extra-small uppercase fw-bold" style="max-width: 250px;">{{ $student->perfil_profesional_carrera }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center" data-search="{{ $student->semestre }}°">
                                <span class="badge bg-light text-dark border-0 rounded-3 px-3 py-2 fw-800">
                                    {{ $student->semestre }}°
                                </span>
                            </td>
                            <td>
                                @if($student->status == 'BAJA')
                                    <span class="status-pill status-baja px-3 py-2 extra-small fw-800 uppercase"><i class="bi bi-circle-fill me-1 small"></i> Baja</span>
                                @elseif($student->status == 'LIBERACION')
                                    <span class="status-pill status-liberado px-3 py-2 extra-small fw-800 uppercase"><i class="bi bi-circle-fill me-1 small"></i> Liberado</span>
                                @else
                                    <span class="status-pill status-inscrito px-3 py-2 extra-small fw-800 uppercase"><i class="bi bi-circle-fill me-1 small"></i> Inscrito</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-3 shadow-none border-0 p-2" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical fs-6 text-muted"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-2">
                                        <li>
                                            <a class="dropdown-item rounded-3 py-2 fw-bold" href="{{ route('students.show', $student->id_estudiante) }}">
                                                <i class="bi bi-person-badge text-primary me-2"></i> Perfil Completo
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item rounded-3 py-2 fw-bold" href="{{ route('students.edit', $student->id_estudiante) }}">
                                                <i class="bi bi-pencil-square text-warning me-2"></i> Editar Datos
                                            </a>
                                        </li>
                                        
                                        @if($student->status != 'BAJA')
                                        <li><hr class="dropdown-divider opacity-50"></li>
                                        <li>
                                            <button type="button" class="dropdown-item rounded-3 py-2 text-danger fw-800 uppercase extra-small" 
                                                onclick="prepararBaja('{{ $student->id_estudiante }}', '{{ $student->nombre }} {{ $student->apellido_paterno }}')">
                                                <i class="bi bi-person-dash me-2"></i> Registrar Baja
                                            </button>
                                        </li>
                                        @endif

                                        @if($student->status != 'LIBERACION' && $student->status != 'BAJA')
                                        <li>
                                            <button type="button" class="dropdown-item rounded-3 py-2 text-success fw-800 uppercase extra-small" 
                                                    onclick="prepararLiberacion('{{ $student->id_estudiante }}', '{{ $student->nombre }} {{ $student->apellido_paterno }}')">
                                                <i class="bi bi-patch-check me-2"></i> Marcar Liberado
                                            </button>
                                        </li>
                                        @endif

                                        <li><hr class="dropdown-divider opacity-50"></li>
                                        <li>
                                            <button type="button" class="dropdown-item rounded-3 py-2 text-danger opacity-75 small" 
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

{{-- MODALES ESTILIZADOS --}}

<div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 text-center">
            <div class="modal-body p-4">
                <div class="bg-danger-subtle text-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 70px; height: 70px; background: rgba(220, 53, 69, 0.1);">
                    <i class="bi bi-trash3 fs-1"></i>
                </div>
                <h5 class="fw-800 text-dark">¿Eliminar registro?</h5>
                <p class="text-muted small">Esta acción borrará permanentemente a:<br><strong id="nombreEliminar" class="text-dark fs-6"></strong></p>
                <form id="formEliminar" method="POST">
                    @csrf @method('DELETE')
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-danger rounded-3 fw-800 uppercase extra-small py-2 shadow-sm">Sí, eliminar definitivamente</button>
                        <button type="button" class="btn btn-light rounded-3 fw-bold py-2" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalBaja" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pt-4 px-4 pb-0">
                <h5 class="modal-title fw-800 text-danger d-flex align-items-center"><i class="bi bi-exclamation-triangle-fill fs-4 me-2"></i>Formulario de Baja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formBaja" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="p-3 bg-danger-subtle rounded-4 mb-4 border border-danger-subtle" style="background: rgba(220, 53, 69, 0.05);">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="avatar-letter d-flex align-items-center justify-content-center fw-800 fs-4 uppercase rounded-3" style="width: 50px; height: 50px; background: white; color: #dc3545; border: 1px solid rgba(220, 53, 69, 0.2);">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                            </div>
                            <div class="col">
                                <p class="text-muted small mb-0 text-uppercase Extra-small fw-bold">Alumno a procesar:</p>
                                <h5 id="nombreAlumnoBaja" class="fw-800 text-dark mb-0"></h5>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label fw-800 text-muted extra-small uppercase mb-1">Fecha de Baja</label>
                            <input type="date" name="fecha_baja" class="form-control border-0 bg-light rounded-3 shadow-sm p-3 fw-bold text-dark" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-800 text-muted extra-small uppercase mb-1">Motivo Detallado de la Baja</label>
                            <textarea name="motivo_baja" class="form-control border-0 bg-light rounded-3 shadow-sm p-3" rows="5" placeholder="Justificación administrativa o personal para la baja..." required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-4 fw-bold py-2" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger rounded-3 px-4 shadow-sm fw-800 uppercase extra-small py-2">Confirmar Baja Administrativa</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalLiberacion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pt-4 px-4 pb-0">
                <h5 class="modal-title fw-800 d-flex align-items-center" style="color: var(--tesvb-green)"><i class="bi bi-patch-check-fill fs-4 me-2"></i>Modalidad de Liberación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formLiberacion" method="POST">
                @csrf
                <div class="modal-body p-4">
                     <div class="p-3 bg-light rounded-4 mb-4 border" style="background: rgba(0, 127, 63, 0.03);">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="avatar-letter d-flex align-items-center justify-content-center fw-800 fs-4 uppercase rounded-3" style="width: 50px; height: 50px; background: white; color: var(--tesvb-green); border: 1px solid rgba(0, 127, 63, 0.2);">
                                    <i class="bi bi-mortarboard-fill"></i>
                                </div>
                            </div>
                            <div class="col">
                                <p class="text-muted small mb-0 text-uppercase Extra-small fw-bold">Alumno a liberar:</p>
                                <h5 id="nombreAlumnoLibera" class="fw-800 text-dark mb-0"></h5>
                            </div>
                        </div>
                    </div>

                    <p class="text-muted small fw-800 uppercase extra-small mb-3">Seleccione el fundamento para la liberación:</p>
                    
                    <div class="d-flex flex-column gap-3">
                        <label class="option-card p-3 border rounded-4 position-relative d-flex align-items-center shadow-sm">
                            <input class="form-check-input mt-0 me-3 custom-check" type="radio" name="modalidad_liberacion" id="mod1" value="CONCLUCION DE SERVICIO SOCIAL" required>
                            <div>
                                <span class="fw-800 text-dark fs-6 d-block">Conclusión de Servicio Social</span>
                                <small class="text-muted fw-bold extra-small uppercase">Término estándar de las horas reglamentarias.</small>
                            </div>
                        </label>
                        <label class="option-card p-3 border rounded-4 position-relative d-flex align-items-center shadow-sm">
                            <input class="form-check-input mt-0 me-3 custom-check" type="radio" name="modalidad_liberacion" id="mod2" value="ARTICULO 21 ESTATAL-91 FEDERAL">
                            <div>
                                <span class="fw-800 text-dark fs-6 d-block">Artículo 21 Estatal / 91 Federal</span>
                                <small class="text-muted fw-bold extra-small uppercase">Liberación por desempeño laboral o similar.</small>
                            </div>
                        </label>
                        <label class="option-card p-3 border rounded-4 position-relative d-flex align-items-center shadow-sm">
                            <input class="form-check-input mt-0 me-3 custom-check" type="radio" name="modalidad_liberacion" id="mod3" value="ARTICULO 19">
                            <div>
                                <span class="fw-800 text-dark fs-6 d-block">Artículo 19</span>
                                <small class="text-muted fw-bold extra-small uppercase">Casos especiales contemplados en reglamento.</small>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-4 fw-bold py-2" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn rounded-3 px-4 shadow-sm fw-800 uppercase extra-small py-2 text-white" style="background: var(--tesvb-green)">Confirmar Liberación</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .fw-800 { font-weight: 800; }
    .uppercase { text-transform: uppercase; }
    .extra-small { 
        font-size: 0.65rem; 
        letter-spacing: 0.8px; 
        color: #adb5bd; 
    }
    
    .card-premium {
        border-radius: 20px;
        transition: all 0.3s ease;
    }
    .card-premium:hover {
        box-shadow: 0 10px 30px rgba(0,0,0,0.08) !important;
    }

    .btn-tesvb {
        background-color: var(--tesvb-green);
        color: white;
        font-weight: 800;
        border-radius: 12px;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border: none;
    }
    .btn-tesvb:hover {
        background-color: #006331;
        color: white;
    }

    .status-pill {
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
    }
    .status-inscrito { background: rgba(0, 127, 63, 0.1); color: var(--tesvb-green); }
    .status-liberado { background: rgba(13, 110, 253, 0.1); color: #0d6efd; }
    .status-baja { background: rgba(220, 53, 69, 0.1); color: #dc3545; }
    
    .grayscale { filter: grayscale(1); opacity: 0.6; }

    table.dataTable thead th { border-bottom: 1px solid #eee !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--tesvb-green) !important;
        border-color: var(--tesvb-green) !important;
        color: white !important;
        border-radius: 10px;
        font-weight: bold;
    }
    .dataTables_filter input {
        border: 0 !important;
        background: #f8fafc !important;
        border-radius: 10px !important;
        padding: 10px !important;
    }

    .option-card {
        background: white;
        border-color: #eee !important;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .option-card:hover {
        border-color: var(--tesvb-green) !important;
        background: rgba(0, 127, 63, 0.02);
    }
    .custom-check:checked + div span {
        color: var(--tesvb-green) !important;
    }
    .custom-check:checked {
        background-color: var(--tesvb-green);
        border-color: var(--tesvb-green);
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        var table = $('#mainTable').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
            pageLength: 25,
            order: [[1, 'asc']], 
            dom: '<"d-flex justify-content-between align-items-center p-4"f>rt<"d-flex justify-content-between align-items-center p-4"ip>',
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
        $('#formEliminar').attr('action', "{{ route('students.destroy', ':id') }}".replace(':id', id));
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