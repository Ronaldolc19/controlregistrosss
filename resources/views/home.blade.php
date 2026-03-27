@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- Encabezado --}}
    <div class="row mb-4">
        <div class="col-12 text-center text-md-start">
            <h2 class="fw-bold text-dark mb-0">Panel de <span class="text-primary">Gestión 2026</span></h2>
            <p class="text-muted small">Control Escolar de Servicio Social - Universidad Tecnológica</p>
        </div>
    </div>

    {{-- Cards de Resumen --}}
    <div class="row g-4 mb-5 text-center text-md-start">
        <div class="col-md-4">
            <a href="{{ route('students.index') }}" class="text-decoration-none">
                <div class="card bento-card border-0 shadow-sm p-4 h-100 hover-card border-start border-primary border-5">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted text-uppercase fw-bold">Estudiantes Activos</small>
                            <h2 class="fw-bold mt-2 mb-0">{{ $totalEstudiantes }}</h2>
                        </div>
                        <div class="bg-primary-subtle text-primary p-3 rounded-4">
                            <i class="bi bi-people-fill fs-3"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ route('students.bajas.list') }}" class="text-decoration-none">
                <div class="card bento-card border-0 shadow-sm p-4 h-100 hover-card border-start border-danger border-5">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted text-uppercase fw-bold">Bajas Registradas</small>
                            <h2 class="fw-bold mt-2 mb-0">{{ $totalBajas }}</h2>
                        </div>
                        <div class="bg-danger-subtle text-danger p-3 rounded-4">
                            <i class="bi bi-person-x-fill fs-3"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            {{-- Redirección agregada a la card de Liberados --}}
            <a href="{{ route('students.liberados.list') }}" class="text-decoration-none">
                <div class="card bento-card border-0 shadow-sm p-4 h-100 hover-card border-start border-success border-5">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted text-uppercase fw-bold">Liberados</small>
                            <h2 class="fw-bold mt-2 mb-0">{{ $totalLiberados }}</h2>
                        </div>
                        <div class="bg-success-subtle text-success p-3 rounded-4">
                            <i class="bi bi-patch-check-fill fs-3"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <hr class="mb-5 opacity-25">

    {{-- Acciones y Carga de Datos --}}
    <div class="row g-4">
        <div class="col-12">
            <h5 class="fw-bold mb-3"><i class="bi bi-gear-fill me-2 text-secondary"></i>Acciones y Carga de Datos</h5>
        </div>

        <div class="col-md-4">
            <button class="btn btn-white bento-card w-100 py-4 shadow-sm border-0 fw-bold hover-card text-center d-flex flex-column align-items-center" 
                    data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="bi bi-file-earmark-spreadsheet text-primary fs-1 mb-2"></i>
                <span>Cargar Dataset Inscripción</span>
            </button>
        </div>

        <div class="col-md-4">
            <button class="btn btn-white bento-card w-100 py-4 shadow-sm border-0 fw-bold hover-card text-center d-flex flex-column align-items-center" 
                    data-bs-toggle="modal" data-bs-target="#bajasModal">
                <i class="bi bi-file-earmark-excel text-danger fs-1 mb-2"></i>
                <span>Cargar Dataset Bajas</span>
            </button>
        </div>

        <div class="col-md-4">
            <a href="{{ route('students.index') }}" class="btn btn-primary bento-card w-100 py-4 shadow-sm border-0 fw-bold hover-card text-center d-flex flex-column align-items-center justify-content-center h-100 text-white">
                <i class="bi bi-table fs-1 mb-2"></i>
                <span>Ver Expediente General</span>
            </a>
        </div>
    </div>
</div>

@include('partials.modals_import')

<style>
    .bento-card {
        border-radius: 20px;
        background: #ffffff;
    }
    .hover-card {
        transition: 0.3s ease;
    }
    .hover-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.1) !important;
    }
    .btn-white {
        background-color: white;
        color: #333;
    }
</style>
@endsection