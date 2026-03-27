<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bento-card border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold"><i class="bi bi-file-earmark-excel text-primary me-2"></i>Cargar Inscripciones</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            {{-- Importante: El action debe coincidir con tu ruta de importación --}}
            <form action="{{ route('import.inscripcion') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body text-center p-4">
                    <div class="bg-primary-subtle rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="bi bi-cloud-arrow-up text-primary fs-1"></i>
                    </div>
                    <p class="fw-bold mb-1">Dataset de Nuevos Ingresos</p>
                    <p class="text-muted small mb-4">El sistema ignorará automáticamente las filas vacías o con formato al final del archivo.</p>
                    
                    <input type="file" name="file" class="form-control rounded-pill border-2 @error('file') is-invalid @enderror" accept=".csv,.xlsx,.xls" required>
                    @error('file')
                        <div class="text-danger extra-small mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                        <i class="bi bi-gear-fill me-2"></i>Procesar Excel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="bajasModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bento-card border-0 shadow-lg">
            <div class="modal-header border-0 text-danger pb-0">
                <h5 class="fw-bold"><i class="bi bi-person-x-fill me-2"></i>Reporte de Bajas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('import.bajas') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-danger border-0 rounded-4 small mb-4 d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                        <div>
                            <strong>Atención:</strong> Se actualizará el estatus a "BAJA" únicamente para las matrículas encontradas en este archivo.
                        </div>
                    </div>
                    
                    <label class="form-label fw-bold small text-muted text-uppercase ms-2">Seleccionar archivo de bajas</label>
                    <input type="file" name="file" class="form-control rounded-pill border-2" accept=".csv,.xlsx,.xls" required>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" class="btn btn-danger rounded-pill px-5 fw-bold shadow-sm text-white">
                        <i class="bi bi-person-dash me-2"></i>Aplicar Bajas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .bg-primary-subtle { background-color: rgba(13, 110, 253, 0.1) !important; }
    .extra-small { font-size: 0.75rem; }
    .bento-card { border-radius: 25px; }
</style>