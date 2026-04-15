<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Control registros SS - TESVB</title>

    {{-- Fuentes y Estilos Base --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- CSS de DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <style>
        :root {
            --tesvb-green: #007F3F;
            --tesvb-bright: #00a854;
            --tesvb-wine: #800020;
            --sidebar-bg: #0f172a; 
            --app-bg: #f8fafc; 
            --transition-curve: cubic-bezier(0.25, 1, 0.5, 1);
            --sidebar-width: 280px;
            --sidebar-collapsed: 85px;
        }

        body {
            background-color: var(--app-bg);
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1e293b;
            overflow-x: hidden;
        }

        /* --- SIDEBAR --- */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: var(--sidebar-bg);
            z-index: 1050;
            transition: all 0.4s var(--transition-curve);
            border-right: 1px solid rgba(255,255,255,0.05);
            overflow-y: auto;
        }

        #sidebar.collapsed { width: var(--sidebar-collapsed); }

        .sidebar-header { padding: 2.5rem 1.5rem; text-align: center; }
        .sidebar-header h3 { color: #ffffff !important; font-weight: 800; }

        .nav-category {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 800;
            color: #475569;
            padding: 1.5rem 1.5rem 0.5rem;
        }

        .nav-link-custom {
            color: #94a3b8;
            font-weight: 600;
            padding: 0.85rem 1.5rem;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.4s var(--transition-curve);
            margin: 0.2rem 1rem;
            border-radius: 12px;
        }

        .nav-link-custom i { font-size: 1.25rem; margin-right: 1rem; }

        .nav-link-custom:hover {
            color: white;
            background: rgba(255, 255, 255, 0.05);
            transform: translateX(4px);
        }

        .nav-link-custom.active {
            color: white;
            background: var(--tesvb-green);
            box-shadow: 0 4px 15px rgba(0, 127, 63, 0.3);
        }

        #sidebar.collapsed .nav-text, 
        #sidebar.collapsed .nav-category,
        #sidebar.collapsed .sidebar-header h3,
        #sidebar.collapsed .sidebar-header span { display: none; }

        #sidebar.collapsed .nav-link-custom { justify-content: center; margin: 0.2rem 0.5rem; }
        #sidebar.collapsed .nav-link-custom i { margin-right: 0; }

        /* --- WRAPPERS --- */
        .navbar-glass, .main-wrapper {
            margin-left: var(--sidebar-width);
            transition: all 0.4s var(--transition-curve);
        }

        .navbar-glass.expanded, .main-wrapper.expanded { margin-left: var(--sidebar-collapsed); }

        .navbar-glass {
            padding: 1rem 2rem;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .main-wrapper { padding: 2.5rem; }

        /* AJUSTE DATATABLES SEARCH */
        .dataTables_filter { margin-bottom: 1.5rem; }
        .dataTables_filter input {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            padding: 0.5rem 1rem;
            outline: none;
        }

        @media (max-width: 992px) {
            #sidebar { left: -280px; }
            #sidebar.active { left: 0; }
            .navbar-glass, .main-wrapper { margin-left: 0 !important; }
        }
    </style>
    
    @stack('styles')
</head>
<body>

    @auth
    <nav id="sidebar">
        <div class="sidebar-header">
            <h3>Control registros<span style="color: var(--tesvb-green)">.</span></h3>
            <span class="small text-uppercase fw-bold text-white-50" style="letter-spacing: 2px">TESVB</span>
        </div>

        <div class="nav-category">Principal</div>
        <a href="{{ route('home') }}" class="nav-link-custom {{ request()->routeIs('home') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i> 
            <span class="nav-text">Dashboard</span>
        </a>

        <div class="nav-category">Gestión Escolar</div>
        <a href="{{ route('students.index') }}" class="nav-link-custom {{ request()->routeIs('students.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> 
            <span class="nav-text">Estudiantes</span>
        </a>
        <a href="{{ route('bajas.index') }}" class="nav-link-custom {{ request()->routeIs('bajas.*') ? 'active' : '' }}">
            <i class="bi bi-person-x-fill"></i> 
            <span class="nav-text">Control de Bajas</span>
        </a>

        <div class="nav-category">Certificación</div>
        <a href="{{ route('liberaciones.index') }}" class="nav-link-custom {{ request()->routeIs('liberaciones.*') ? 'active' : '' }}">
            <i class="bi bi-patch-check-fill"></i> 
            <span class="nav-text">Liberaciones</span>
        </a>
        <a href="{{ route('constancias.index') }}" class="nav-link-custom {{ request()->routeIs('constancias.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text-fill"></i> 
            <span class="nav-text">Constancias</span>
        </a>
    </nav>
    @endauth

    <div id="content-area">
        @auth
        <nav class="navbar-glass d-flex justify-content-between align-items-center">
            <button class="btn" id="toggleSidebar">
                <i class="bi bi-text-indent-left fs-3"></i>
            </button>
            
            <div class="d-none d-md-block">
                <span class="text-muted fw-bold">Gestión de Servicio Social</span>
            </div>

            <div class="dropdown">
                <a class="d-flex align-items-center text-decoration-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                    <div class="text-end me-3 d-none d-sm-block">
                        <p class="mb-0 text-dark fw-bold small">{{ Auth::user()->name }}</p>
                        <p class="mb-0 text-muted" style="font-size: 0.7rem;">ADMINISTRADOR</p>
                    </div>
                    <div class="bg-white rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border: 2px solid var(--tesvb-green);">
                        <i class="bi bi-person-fill text-dark fs-5"></i>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-2 mt-3">
                    <li><a class="dropdown-item rounded-3 p-2" href="#"><i class="bi bi-gear me-2"></i> Perfil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item rounded-3 p-2 text-danger fw-bold" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        @endauth

        <main class="{{ Auth::check() ? 'main-wrapper' : 'p-0' }}">
            @yield('content')
        </main>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>

    {{-- SCRIPTS: EL ORDEN ES VITAL --}}
    {{-- 1. jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    {{-- 2. Bootstrap Bundle --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    {{-- 3. DataTables JS --}}
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Unificar Toggle Sidebar
            $('#toggleSidebar').on('click', function() {
                if ($(window).width() < 992) {
                    $('#sidebar').toggleClass('active');
                } else {
                    $('#sidebar').toggleClass('collapsed');
                    $('.navbar-glass, .main-wrapper').toggleClass('expanded');
                }
                
                // Cambiar icono
                $(this).find('i').toggleClass('bi-text-indent-left bi-text-indent-right');
            });

            // Cerrar sidebar al hacer clic fuera en móviles
            $(document).on('click', function(e) {
                if ($(window).width() < 992) {
                    if (!$(e.target).closest('#sidebar, #toggleSidebar').length) {
                        $('#sidebar').removeClass('active');
                    }
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>