@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- 1. ENCABEZADO ESTRATÉGICO --}}
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-0">Panel de <span class="text-primary">Control 2026</span></h2>
                <p class="text-muted small">Métricas de Impacto y Gestión de Servicio Social</p>
            </div>
            <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="bi bi-plus-lg me-2"></i>Importar Dataset
            </button>
        </div>
    </div>

    {{-- 2. CARDS DE RESUMEN (KPIs) --}}
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card bento-card border-0 shadow-sm p-3 border-start border-primary border-5">
                <div class="d-flex align-items-center">
                    <div class="bg-primary-subtle text-primary p-3 rounded-4 me-3">
                        <i class="bi bi-people-fill fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted fw-bold text-uppercase">Activos</small>
                        <h3 class="fw-bold mb-0">{{ $totalEstudiantes }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bento-card border-0 shadow-sm p-3 border-start border-danger border-5">
                <div class="d-flex align-items-center">
                    <div class="bg-danger-subtle text-danger p-3 rounded-4 me-3">
                        <i class="bi bi-person-x-fill fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted fw-bold text-uppercase">Bajas</small>
                        <h3 class="fw-bold mb-0">{{ $totalBajas }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bento-card border-0 shadow-sm p-3 border-start border-success border-5">
                <div class="d-flex align-items-center">
                    <div class="bg-success-subtle text-success p-3 rounded-4 me-3">
                        <i class="bi bi-patch-check-fill fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted fw-bold text-uppercase">Liberados</small>
                        <h3 class="fw-bold mb-0">{{ $totalLiberados }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bento-card border-0 shadow-sm p-3 border-start border-warning border-5">
                <div class="d-flex align-items-center">
                    <div class="bg-warning-subtle text-warning p-3 rounded-4 me-3">
                        <i class="bi bi-file-earmark-pdf-fill fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted fw-bold text-uppercase">Constancias</small>
                        <h3 class="fw-bold mb-0">{{ $totalConstancias }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. DASHBOARD DE ANALÍTICA --}}
    <div class="row g-4 mb-5">
        {{-- Gráfico: Distribución por Carrera --}}
        <div class="col-md-8">
            <div class="card bento-card border-0 shadow-sm p-4 h-100">
                <h6 class="fw-bold text-muted mb-4 small">PRODUCCIÓN DE CONSTANCIAS POR CARRERA</h6>
                <canvas id="chartCarreras" height="300"></canvas>
            </div>
        </div>

        {{-- Gráfico: Estacionalidad por Periodos --}}
        <div class="col-md-4">
            <div class="card bento-card border-0 shadow-sm p-4 h-100">
                <h6 class="fw-bold text-muted mb-4 small">DISTRIBUCIÓN POR PERIODOS</h6>
                <canvas id="chartPeriodos"></canvas>
            </div>
        </div>

        {{-- Gráfico: Top Empresas/Dependencias --}}
        <div class="col-md-12">
            <div class="card bento-card border-0 shadow-sm p-4">
                <h6 class="fw-bold text-muted mb-4 small">TOP DEPENDENCIAS CON MÁS LIBERACIONES</h6>
                <canvas id="chartEmpresas" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

@include('partials.modals_import')

{{-- SCRIPTS DE DASHBOARD --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Configuración global de fuentes
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.color = '#64748b';

    // 1. Gráfico de Carreras (Barras horizontales)
    new Chart(document.getElementById('chartCarreras'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($constanciasCarrera->pluck('carrera')) !!},
            datasets: [{
                label: 'Total Constancias',
                data: {!! json_encode($constanciasCarrera->pluck('total')) !!},
                backgroundColor: '#4f46e5',
                borderRadius: 8,
                barThickness: 20
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { x: { grid: { display: false } }, y: { grid: { display: false } } }
        }
    });

    // 2. Gráfico de Periodos (Donut)
    new Chart(document.getElementById('chartPeriodos'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($constanciasPeriodo->pluck('periodo')) !!},
            datasets: [{
                data: {!! json_encode($constanciasPeriodo->pluck('total')) !!},
                backgroundColor: ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                hoverOffset: 15,
                borderWidth: 0
            }]
        },
        options: {
            plugins: { legend: { position: 'bottom' } },
            cutout: '70%'
        }
    });

    // 3. Gráfico de Empresas (Barras verticales)
    new Chart(document.getElementById('chartEmpresas'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($constanciasEmpresa->pluck('empresa')) !!},
            datasets: [{
                label: 'Constancias por Empresa',
                data: {!! json_encode($constanciasEmpresa->pluck('total')) !!},
                backgroundColor: '#f59e0b',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>

<style>
    .bento-card { border-radius: 20px; background: white; }
    .bg-primary-subtle { background-color: #eef2ff; }
    .bg-danger-subtle { background-color: #fef2f2; }
    .bg-success-subtle { background-color: #f0fdf4; }
    .bg-warning-subtle { background-color: #fffbeb; }
</style>
@endsection