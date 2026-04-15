@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-3">
        <div class="col-12">
            <h2 class="fw-bold text-dark">Editar Expediente Completo</h2>
            <p class="text-muted">ID Estudiante: <span class="badge bg-secondary">{{ $student->id_estudiante }}</span> | Estatus actual: <strong>{{ $student->status }}</strong></p>
        </div>
    </div>

    {{-- BLOQUE DE ALERTAS PERSONALIZADO --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 15px;">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-octagon-fill fs-4 me-3"></i>
                <div>
                    <strong class="d-block">No se pudo guardar el registro:</strong>
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('students.update', $student->id_estudiante) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Menú de Pestañas --}}
        <ul class="nav nav-tabs border-0 mb-4" id="editStudentTab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active fw-bold border-0 shadow-sm rounded-3 me-2" id="escolar-tab" data-bs-toggle="tab" data-bs-target="#escolar" type="button">1. Datos de la Escuela</button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-bold border-0 shadow-sm rounded-3 me-2" id="alumno-tab" data-bs-toggle="tab" data-bs-target="#alumno" type="button">2. Datos del Alumno</button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-bold border-0 shadow-sm rounded-3 me-2" id="servicio-tab" data-bs-toggle="tab" data-bs-target="#servicio" type="button">3. Servicio Social / Dependencia</button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-bold border-0 shadow-sm rounded-3" id="socio-tab" data-bs-toggle="tab" data-bs-target="#socio" type="button">4. Perfil Sociocultural</button>
            </li>
        </ul>

        <div class="tab-content pt-2" id="editStudentTabContent">
            
            {{-- SECCIÓN 1: DATOS DE LA ESCUELA --}}
            <div class="tab-pane fade show active" id="escolar" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small">CLAVE CCT</label>
                            <input type="text" name="clave_cct" class="form-control bg-light border-0" value="{{ old('clave_cct', $student->clave_cct) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small">SUBSISTEMA</label>
                            <input type="text" name="subsistema" class="form-control bg-light border-0" value="{{ old('subsistema', $student->subsistema) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small">NOMBRE DE ESCUELA</label>
                            <input type="text" name="nombre_escuela" class="form-control bg-light border-0" value="{{ old('nombre_escuela', $student->nombre_escuela) }}">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-bold text-muted small">DIRECCIÓN ESCUELA</label>
                            <input type="text" name="direccion_escuela" class="form-control bg-light border-0" value="{{ old('direccion_escuela', $student->direccion_escuela) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small">MUNICIPIO ESCUELA</label>
                            <input type="text" name="municipio_escuela" class="form-control bg-light border-0" value="{{ old('municipio_escuela', $student->municipio_escuela) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small">TELÉFONO ESCUELA</label>
                            <input type="text" name="telefono_escuela" class="form-control bg-light border-0" value="{{ old('telefono_escuela', $student->telefono_escuela) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small">CORREO ESCUELA</label>
                            <input type="email" name="correo_escuela" class="form-control bg-light border-0" value="{{ old('correo_escuela', $student->correo_escuela) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small">RESPONSABLE SS ESCUELA</label>
                            <input type="text" name="responsable_ss_escuela" class="form-control bg-light border-0" value="{{ old('responsable_ss_escuela', $student->responsable_ss_escuela) }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECCIÓN 2: DATOS DEL ALUMNO --}}
            <div class="tab-pane fade" id="alumno" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small">APELLIDO PATERNO</label>
                            <input type="text" name="apellido_paterno" class="form-control bg-light border-0" value="{{ old('apellido_paterno', $student->apellido_paterno) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small">APELLIDO MATERNO</label>
                            <input type="text" name="apellido_materno" class="form-control bg-light border-0" value="{{ old('apellido_materno', $student->apellido_materno) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small">NOMBRE(S)</label>
                            <input type="text" name="nombre" class="form-control bg-light border-0" value="{{ old('nombre', $student->nombre) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-muted small">NÚMERO DE CUENTA</label>
                            <input type="text" name="num_cuenta" class="form-control bg-light border-0" value="{{ old('num_cuenta', $student->num_cuenta) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-muted small">REGISTRO ESTATAL SS</label>
                            <input type="text" name="registro_estatal_ss" class="form-control bg-light border-0" value="{{ old('registro_estatal_ss', $student->registro_estatal_ss) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-muted small">NIVEL</label>
                            <input type="text" name="nivel" class="form-control bg-light border-0" value="{{ old('nivel', $student->nivel) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-muted small">SEMESTRE</label>
                            <input type="text" name="semestre" class="form-control bg-light border-0" value="{{ old('semestre', $student->semestre) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">CARRERA</label>
                            <input type="text" name="perfil_profesional_carrera" class="form-control bg-light border-0" value="{{ old('perfil_profesional_carrera', $student->perfil_profesional_carrera) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-muted small">INICIO PERIODO</label>
                            <input type="text" name="periodo_inicio" class="form-control bg-light border-0" value="{{ old('periodo_inicio', $student->periodo_inicio) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-muted small">TÉRMINO PERIODO</label>
                            <input type="text" name="periodo_termino" class="form-control bg-light border-0" value="{{ old('periodo_termino', $student->periodo_termino) }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold text-muted small">SEXO</label>
                            <input type="text" name="sexo" class="form-control bg-light border-0" value="{{ old('sexo', $student->sexo) }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold text-muted small">EDAD</label>
                            <input type="number" name="edad" class="form-control bg-light border-0" value="{{ old('edad', $student->edad) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small">PROMEDIO</label>
                            <input type="text" name="promedio" class="form-control bg-light border-0" value="{{ old('promedio', $student->promedio) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small">% CUBIERTO PLAN</label>
                            <input type="text" name="porcentaje_cubierto_plan" class="form-control bg-light border-0" value="{{ old('porcentaje_cubierto_plan', $student->porcentaje_cubierto_plan) }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECCIÓN 3: DEPENDENCIA Y SERVICIO SOCIAL --}}
            <div class="tab-pane fade" id="servicio" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold text-muted small">DEPENDENCIA RECEPTORA</label>
                            <input type="text" name="nombre_dependencia_receptora" class="form-control bg-light border-0" value="{{ old('nombre_dependencia_receptora', $student->nombre_dependencia_receptora) }}">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-bold text-muted small">DIRECCIÓN DEPENDENCIA</label>
                            <input type="text" name="direccion_dependencia" class="form-control bg-light border-0" value="{{ old('direccion_dependencia', $student->direccion_dependencia) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small">MUNICIPIO DEPENDENCIA</label>
                            <input type="text" name="municipio_dependencia" class="form-control bg-light border-0" value="{{ old('municipio_dependencia', $student->municipio_dependencia) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small">SECTOR</label>
                            <input type="text" name="sector" class="form-control bg-light border-0" value="{{ old('sector', $student->sector) }}">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-bold text-muted small">RESPONSABLE DEPENDENCIA</label>
                            <input type="text" name="nombre_responsable_dependencia" class="form-control bg-light border-0" value="{{ old('nombre_responsable_dependencia', $student->nombre_responsable_dependencia) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small">HORARIO SERVICIO</label>
                            <input type="text" name="horario_servicio" class="form-control bg-light border-0" value="{{ old('horario_servicio', $student->horario_servicio) }}">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-bold text-muted small">PROYECTO EN EL QUE PARTICIPA</label>
                            <input type="text" name="proyecto_participa" class="form-control bg-light border-0" value="{{ old('proyecto_participa', $student->proyecto_participa) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">¿CON O SIN BECA?</label>
                            <input type="text" name="ss_con_o_sin_beca" class="form-control bg-light border-0" value="{{ old('ss_con_o_sin_beca', $student->ss_con_o_sin_beca) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">MONTO ESTIMULO (Opcional)</label>
                            <input type="text" name="monto_estimulo" class="form-control bg-light border-0" value="{{ old('monto_estimulo', $student->monto_estimulo) }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECCIÓN 4: SOCIO-CULTURAL --}}
            <div class="tab-pane fade" id="socio" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">¿HABLA LENGUA INDÍGENA?</label>
                            <input type="text" name="habla_lengua_indigena" class="form-control bg-light border-0" value="{{ old('habla_lengua_indigena', $student->habla_lengua_indigena) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">¿CUÁL LENGUA?</label>
                            <input type="text" name="cual_lengua" class="form-control bg-light border-0" value="{{ old('cual_lengua', $student->cual_lengua) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">¿TIENE DISCAPACIDAD?</label>
                            <input type="text" name="tiene_discapacidad" class="form-control bg-light border-0" value="{{ old('tiene_discapacidad', $student->tiene_discapacidad) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">¿CUÁL DISCAPACIDAD?</label>
                            <input type="text" name="cual_discapacidad" class="form-control bg-light border-0" value="{{ old('cual_discapacidad', $student->cual_discapacidad) }}">
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="mt-4 mb-5 d-flex justify-content-end">
            <a href="{{ route('students.index') }}" class="btn btn-light fw-bold me-2 px-4 py-2 rounded-3">Cancelar</a>
            <button type="submit" class="btn btn-primary fw-bold px-5 py-2 rounded-3 shadow">Guardar Cambios del Expediente</button>
        </div>

    </form>
</div>

<style>
    .nav-tabs .nav-link { color: #6c757d; background-color: #f8f9fa; border: 1px solid #eee !important; margin-bottom: 5px; }
    .nav-tabs .nav-link.active { background-color: #0d6efd !important; color: white !important; box-shadow: 0 4px 12px rgba(13,110,253,0.3) !important; }
    .form-control:focus { background-color: #fff !important; border: 1px solid #0d6efd !important; box-shadow: none; }
</style>
@endsection