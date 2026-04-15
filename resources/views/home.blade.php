@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- 1. ENCABEZADO ESTRATÉGICO --}}
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-800 text-dark mb-0">Panel de <span style="color: #4f46e5;">Control</span></h2>
                <p class="text-muted small">Métricas de Impacto y Gestión de Servicio Social</p>
            </div>
            <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="bi bi-cloud-arrow-up-fill me-2"></i>Importar Dataset
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
                        <small class="text-muted fw-800 text-uppercase extra-small">Activos</small>
                        <h3 class="fw-800 mb-0">{{ number_format($totalEstudiantes) }}</h3>
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
                        <small class="text-muted fw-800 text-uppercase extra-small">Bajas</small>
                        <h3 class="fw-800 mb-0">{{ number_format($totalBajas) }}</h3>
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
                        <small class="text-muted fw-800 text-uppercase extra-small">Liberados</small>
                        <h3 class="fw-800 mb-0">{{ number_format($totalLiberados) }}</h3>
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
                        <small class="text-muted fw-800 text-uppercase extra-small">Constancias</small>
                        <h3 class="fw-800 mb-0">{{ number_format($totalConstancias) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. DASHBOARD DE ANALÍTICA --}}
    <div class="row g-4">
        {{-- Gráfico: Distribución por Carrera --}}
        <div class="col-md-8">
            <div class="card bento-card border-0 shadow-sm p-4 h-100">
                <h6 class="fw-800 text-muted mb-4 small text-uppercase">Producción de Constancias por Carrera</h6>
                <canvas id="chartCarreras" height="300"></canvas>
            </div>
        </div>

        {{-- Gráfico: Estacionalidad por Periodos --}}
        <div class="col-md-4">
            <div class="card bento-card border-0 shadow-sm p-4 h-100 text-center">
                <h6 class="fw-800 text-muted mb-4 small text-uppercase">Distribución por Periodos</h6>
                <canvas id="chartPeriodos"></canvas>
            </div>
        </div>

        {{-- Gráfico: Top Empresas/Dependencias --}}
        <div class="col-md-12">
            <div class="card bento-card border-0 shadow-sm p-4">
                <h6 class="fw-800 text-muted mb-4 small text-uppercase">Top Dependencias con Mayor Impacto Académico</h6>
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

    // 1. Gráfico de Carreras (Barras horizontales estilizadas)
    new Chart(document.getElementById('chartCarreras'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($constanciasCarrera->pluck('carrera')) !!},
            datasets: [{
                label: 'Total Constancias',
                data: {!! json_encode($constanciasCarrera->pluck('total')) !!},
                backgroundColor: '#4f46e5',
                borderRadius: 12,
                barThickness: 15
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { 
                x: { grid: { display: false }, border: { display: false } }, 
                y: { grid: { display: false }, border: { display: false } } 
            }
        }
    });

    // 2. Gráfico de Periodos (Donut Pro)
    new Chart(document.getElementById('chartPeriodos'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($constanciasPeriodo->pluck('periodo')) !!},
            datasets: [{
                data: {!! json_encode($constanciasPeriodo->pluck('total')) !!},
                backgroundColor: ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#6366f1'],
                borderWidth: 4,
                borderColor: '#ffffff',
                hoverOffset: 20
            }]
        },
        options: {
            plugins: { 
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } } 
            },
            cutout: '75%'
        }
    });

    // 3. Gráfico de Empresas (Análisis de Volumen)
    new Chart(document.getElementById('chartEmpresas'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($constanciasEmpresa->pluck('empresa')) !!},
            datasets: [{
                label: 'Impacto por Dependencia',
                data: {!! json_encode($constanciasEmpresa->pluck('total')) !!},
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                borderColor: '#4f46e5',
                borderWidth: 2,
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { 
                y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                x: { grid: { display: false } }
            }
        }
    });
</script>

<style>
    .bento-card { border-radius: 24px; background: white; transition: all 0.3s ease; }
    .bg-primary-subtle { background-color: #eef2ff !important; }
    .bg-danger-subtle { background-color: #fef2f2 !important; }
    .bg-success-subtle { background-color: #f0fdf4 !important; }
    .bg-warning-subtle { background-color: #fffbeb !important; }
    .fw-800 { font-weight: 800; }
    .extra-small { font-size: 0.7rem; }
</style>
@endsection