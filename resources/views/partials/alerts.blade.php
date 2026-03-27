@if (session('status'))
    <div class="alert alert-success alert-dismissible fade show bento-card border-0 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show bento-card border-0 shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session()->has('import_errors'))
    <div class="alert alert-warning bento-card border-0 shadow-sm" role="alert">
        <h6 class="fw-bold"><i class="bi bi-list-task me-2"></i> Errores de validación en el Excel:</h6>
        <ul class="mb-0 small">
            @foreach (session()->get('import_errors') as $failure)
                <li>Fila {{ $failure->row() }}: {{ $failure->errors()[0] }}</li>
            @endforeach
        </ul>
    </div>
@endif