<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Biblioteca Virtual')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { background: linear-gradient(135deg, #2c3e50, #3498db); }
        .sidebar { background: white; min-height: calc(100vh - 76px); box-shadow: 2px 0 10px rgba(0,0,0,0.1); }
        .sidebar .nav-link { color: #2c3e50; font-weight: 500; margin: 2px 0; border-radius: 8px; }
        .sidebar .nav-link:hover { background-color: #3498db; color: white; }
        .sidebar .nav-link.active { background-color: #2c3e50; color: white; }
        .card { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .card-header { background: linear-gradient(135deg, #2c3e50, #3498db); color: white; border-radius: 15px 15px 0 0 !important; }
        .btn-primary { background: linear-gradient(135deg, #3498db, #2c3e50); border: none; border-radius: 25px; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-book"></i> Biblioteca Virtual
            </a>
            
            @if(session('usuario_logueado'))
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user"></i> {{ session('usuario_nombre') }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('logout') }}"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            @if(session('usuario_logueado'))
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <div class="p-3">
                    <nav class="nav flex-column">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                        
                        @if(session('usuario_rol') == 'BIBLIOTECARIO')
                        <a class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}" href="{{ route('usuarios.index') }}">
                            <i class="fas fa-users"></i> Usuarios
                        </a>
                        @endif
                        
                        <a class="nav-link {{ request()->routeIs('libros.*') ? 'active' : '' }}" href="{{ route('libros.index') }}">
                            <i class="fas fa-book"></i> Libros
                        </a>
                    </nav>
                </div>
            </div>
            @endif

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>