@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0" style="border-radius: 20px; overflow: hidden;">
        {{-- Header con estilo institucional --}}
        <div class="card-header py-4" style="background: linear-gradient(45deg, #007f3f, #005a2d); border: none;">
            <h4 class="mb-0 fw-800 text-white text-uppercase" style="letter-spacing: 1px;">
                <i class="bi bi-person-plus-fill me-2"></i>Registro Completo de Servicio Social
            </h4>
        </div>

        <div class="card-body p-4 p-md-5">
            {{-- Mensajes de Alerta --}}
            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4" role="alert">
                    <div class="fw-800 text-uppercase small mb-2">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Corregir errores:
                    </div>
                    <ul class="mb-0 mt-2 small fw-bold">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 text-white d-flex align-items-center" style="background-color: #007f3f;" role="alert">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i> 
                    <span class="fw-bold">{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('students.store') }}" method="POST">
                @csrf

                {{-- SECCIÓN A: INSTITUCIÓN --}}
                <div class="d-flex align-items-center mb-3">
                    <span class="badge rounded-3 px-3 py-2 me-2" style="background-color: #007f3f; font-weight: 800;">A</span>
                    <h5 class="mb-0 fw-800 text-dark text-uppercase small" style="letter-spacing: 0.5px;">Datos de la Institución</h5>
                </div>
                
                <div class="row g-3 mb-5 p-4 rounded-4 border" style="background-color: #f8fafc; border-style: dashed !important;">
                    <div class="col-md-3">
                        <label class="form-label-premium">CLAVE CCT</label>
                        <input type="text" name="clave_cct" class="form-control-premium" value="15EIT0013G" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label-premium">SUBSISTEMA</label>
                        <input type="text" name="subsistema" class="form-control-premium" value="SUPERIOR" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-premium">NOMBRE DE LA ESCUELA</label>
                        <input type="text" name="nombre_escuela" class="form-control-premium" value="TECNOLOGICO DE ESTUDIOS SUPERIORES DE VALLE DE BRAVO" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label-premium">DIRECCIÓN DE LA ESCUELA</label>
                        <input type="text" name="direccion_escuela" class="form-control-premium" value="KM 30 CARRETERA FEDERAL MONUMENTO-VALLE DE BRAVO, EJIDO DE SAN ANTONIO DE LA LAGUNA" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label-premium">MUNICIPIO</label>
                        <input type="text" name="municipio_escuela" class="form-control-premium" value="VALLE DE BRAVO" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label-premium">TELÉFONO</label>
                        <input type="text" name="telefono_escuela" class="form-control-premium" value="726 26 6 51 87" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-premium">RESPONSABLE DEL SERVICIO SOCIAL (ESCUELA)</label>
                        <input type="text" name="responsable_ss_escuela" class="form-control-premium" value="LAE MARIA ISABEL SALGUERO SANTANA" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-premium">CORREO</label>
                        <input type="email" name="correo_escuela" class="form-control-premium" value="servicio.social@vbravo.tecnm.mx" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-premium">REGISTRO ESTATAL DE SERVICIO SOCIAL</label>
                        <input type="text" name="registro_estatal_ss" class="form-control-premium" value="15EIT0013G-26-1-N-NC" required>
                    </div>
                </div>

                {{-- SECCIÓN B: ALUMNO --}}
                <div class="d-flex align-items-center mb-3">
                    <span class="badge rounded-3 px-3 py-2 me-2" style="background-color: #007f3f; font-weight: 800;">B</span>
                    <h5 class="mb-0 fw-800 text-dark text-uppercase small" style="letter-spacing: 0.5px;">Datos del Alumno</h5>
                </div>
                
                <div class="row g-3 mb-5">
                    <div class="col-md-4">
                        <label class="form-label-premium">APELLIDO PATERNO</label>
                        <input type="text" name="apellido_paterno" class="form-control-premium" placeholder="Ingrese apellido" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-premium">APELLIDO MATERNO</label>
                        <input type="text" name="apellido_materno" class="form-control-premium" placeholder="Ingrese apellido" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-premium">NOMBRE(S)</label>
                        <input type="text" name="nombre" class="form-control-premium" placeholder="Ingrese nombre(s)" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label-premium">NÚMERO DE CUENTA</label>
                        <input type="text" name="num_cuenta" class="form-control-premium" style="border-left: 4px solid #007f3f !important;" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label-premium">NIVEL</label>
                        <input type="text" name="nivel" class="form-control-premium" value="LICENCIATURA" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-premium">PERFIL PROFESIONAL (CARRERA)</label>
                        <select name="perfil_profesional_carrera" class="form-select-premium shadow-sm" required>
                            <option value="" selected disabled>-- Seleccione Carrera --</option>
                            <option value="ARQUITECTURA">ARQUITECTURA</option>
                            <option value="INGENIERIA CIVIL">INGENIERIA CIVIL</option>
                            <option value="INGENIERIA ELECTRICA">INGENIERIA ELECTRICA</option>
                            <option value="INGENIERIA EN SISTEMAS COMPUTACIONALES">INGENIERIA EN SISTEMAS COMPUTACIONALES</option>
                            <option value="INGENIERIA FORESTAL">INGENIERIA FORESTAL</option>
                            <option value="INGENIERIA INDUSTRIAL">INGENIERIA INDUSTRIAL</option>
                            <option value="INGENIERIA MECATRONICA">INGENIERIA MECATRONICA</option>
                            <option value="LICENCIATURA EN ADMINISTRACION">LICENCIATURA EN ADMINISTRACION</option>
                            <option value="LICENCIATURA EN GASTRONOMIA">LICENCIATURA EN GASTRONOMIA</option>
                            <option value="LICENCIATURA EN TURISMO">LICENCIATURA EN TURISMO</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label-premium">SEMESTRE</label>
                        <input type="text" name="semestre" class="form-control-premium" placeholder="OCTAVO" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label-premium">PERIODO (INICIO)</label>
                        <input type="date" name="periodo_inicio" class="form-control-premium" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label-premium">PERIODO (TÉRMINO)</label>
                        <input type="date" name="periodo_termino" class="form-control-premium" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label-premium">SEXO</label>
                        <select name="sexo" class="form-select-premium">
                            <option value="MASCULINO">MASCULINO</option>
                            <option value="FEMENINO">FEMENINO</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label-premium">EDAD</label>
                        <input type="number" name="edad" class="form-control-premium" required>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label-premium">PROMEDIO</label>
                        <input type="text" name="promedio" class="form-control-premium" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label-premium">% CUBIERTO DEL PLAN</label>
                        <input type="text" name="porcentaje_cubierto_plan" class="form-control-premium" placeholder="Ej: 50%" required>
                    </div>
                </div>

                {{-- SECCIÓN C: DEPENDENCIA --}}
                <div class="d-flex align-items-center mb-3">
                    <span class="badge rounded-3 px-3 py-2 me-2" style="background-color: #007f3f; font-weight: 800;">C</span>
                    <h5 class="mb-0 fw-800 text-dark text-uppercase small" style="letter-spacing: 0.5px;">Dependencia Receptora</h5>
                </div>

                <div class="row g-3 mb-5">
                    <div class="col-md-8">
                        <label class="form-label-premium">NOMBRE DE LA DEPENDENCIA RECEPTORA</label>
                        <input type="text" name="nombre_dependencia_receptora" class="form-control-premium" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-premium">SECTOR</label>
                        <select name="sector" class="form-select-premium">
                            <option value="PUBLICO">PÚBLICO</option>
                            <option value="PRIVADO">PRIVADO</option>
                            <option value="SOCIAL">SOCIAL</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label-premium">DIRECCIÓN (LUGAR DE PRESTACIÓN)</label>
                        <input type="text" name="direccion_dependencia" class="form-control-premium" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-premium">MUNICIPIO (DEPENDENCIA)</label>
                        <input type="text" name="municipio_dependencia" class="form-control-premium" value="VALLE DE BRAVO" required>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label-premium">RESPONSABLE EN DEPENDENCIA</label>
                        <input type="text" name="nombre_responsable_dependencia" class="form-control-premium" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-premium">HORARIO DEL SERVICIO</label>
                        <input type="text" name="horario_servicio" class="form-control-premium" placeholder="LUNES A VIERNES 8:00 A 12:00 HORAS" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-premium">PROYECTO EN QUE PARTICIPA</label>
                        <input type="text" name="proyecto_participa" class="form-control-premium" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-premium">SERVICIO SOCIAL CON O SIN BECA</label>
                        <select name="ss_con_o_sin_beca" class="form-select-premium">
                            <option value="SB (SIN BECA)">SB(SIN BECA)</option>
                            <option value="CB (CON BECA)">CB (CON BECA)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-premium">MONTO DEL ESTÍMULO</label>
                        <input type="text" name="monto_estimulo" class="form-control-premium" placeholder="$1,000">
                    </div>
                </div>

                {{-- INFORMACIÓN ADICIONAL --}}
                <div class="row g-3 mb-5 p-4 rounded-4 border" style="background-color: #f8fafc;">
                    <div class="col-md-3">
                        <label class="form-label-premium">Habla lengua indígena</label>
                        <select name="habla_lengua_indigena" class="form-select-premium">
                            <option value="NO">NO</option>
                            <option value="SI">SI</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label-premium">¿CUÁL?</label>
                        <input type="text" name="cual_lengua" class="form-control-premium" value="N/A">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label-premium">Tiene alguna discapacidad</label>
                        <select name="tiene_discapacidad" class="form-select-premium">
                            <option value="NO">NO</option>
                            <option value="SI">SI</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label-premium">¿CUÁL?</label>
                        <input type="text" name="cual_discapacidad" class="form-control-premium" value="N/A">
                    </div>
                </div>

                {{-- ACCIONES --}}
                <div class="d-flex justify-content-end gap-3 mt-5">
                    <a href="{{ route('students.index') }}" class="btn btn-light px-4 py-2 fw-800 text-uppercase extra-small shadow-sm border rounded-3">Cancelar</a>
                    <button type="submit" class="btn px-5 py-2 fw-800 text-white text-uppercase extra-small shadow" style="background-color: #007f3f; border-radius: 12px; transition: 0.3s;">
                        <i class="bi bi-save2-fill me-2"></i>Guardar Registro
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Tipografía y Textos */
    .fw-800 { font-weight: 800; }
    .extra-small { font-size: 0.75rem; letter-spacing: 0.5px; }
    
    /* Inputs Estilo Premium */
    .form-label-premium {
        font-size: 0.65rem;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control-premium, .form-select-premium {
        border: 1px solid #e2e8f0 !important;
        background-color: #ffffff !important;
        border-radius: 10px !important;
        padding: 0.75rem 1rem !important;
        font-weight: 600;
        color: #1e293b;
        transition: all 0.2s ease;
    }

    .form-control-premium:focus, .form-select-premium:focus {
        border-color: #007f3f !important;
        box-shadow: 0 0 0 4px rgba(0, 127, 63, 0.1) !important;
        outline: none;
    }

    /* Hover effect en botones */
    .btn:hover {
        transform: translateY(-2px);
    }
</style>
@endsection