@extends('layouts.app')

@section('content')
<div class="container d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="row justify-content-center w-100">
        <div class="col-md-5">
            <div class="text-center mb-4">
                <div class="avatar-sm bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="bi bi-shield-lock-fill fs-2"></i>
                </div>
                <h3 class="fw-800 text-uppercase text-primary mb-1">Acceso Administrativo</h3>
                <p class="text-muted small fw-bold text-uppercase">Sistema de Control de Constancias</p>
            </div>

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-5">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="extra-small fw-800 text-secondary text-uppercase mb-1 d-block">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-envelope-fill text-muted"></i></span>
                                <input id="email" type="email" class="form-control bg-light border-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="admin@sistema.com">
                            </div>
                            @error('email')
                                <span class="invalid-feedback d-block mt-2" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="extra-small fw-800 text-secondary text-uppercase mb-1 d-block">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-lock-fill text-muted"></i></span>
                                <input id="password" type="password" class="form-control bg-light border-0 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
                            </div>
                            @error('password')
                                <span class="invalid-feedback d-block mt-2" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label extra-small fw-bold text-muted text-uppercase" for="remember">
                                    Recordar sesión
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg rounded-3 fw-800 extra-small text-uppercase py-3 shadow-sm">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Iniciar Sesión
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <small class="text-muted extra-small fw-bold text-uppercase">
                    &copy; {{ date('Y') }} — Panel de Control Interno
                </small>
            </div>
        </div>
    </div>
</div>

<style>
    body { background-color: #f8fafc; }
    .fw-800 { font-weight: 800; }
    .extra-small { font-size: 0.7rem; letter-spacing: 0.5px; }
    .bg-primary-subtle { background-color: #eef2ff !important; }
    .form-control:focus {
        box-shadow: none;
        background-color: #f1f5f9 !important;
    }
    .input-group-text { border-radius: 8px 0 0 8px; }
    .form-control { border-radius: 0 8px 8px 0; }
</style>
@endsection