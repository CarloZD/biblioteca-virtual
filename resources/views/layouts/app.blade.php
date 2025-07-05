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
        .sidebar .nav-link { color: #2c3e50; font-weight: 500; margin: 2px 0; border-radius: 8px; transition: all 0.3s; }
        .sidebar .nav-link:hover { background-color: #3498db; color: white; transform: translateX(5px); }
        .sidebar .nav-link.active { background-color: #2c3e50; color: white; }
        .sidebar .nav-link.disabled { color: #6c757d; background-color: #f8f9fa; cursor: not-allowed; }
        .card { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .card-header { background: linear-gradient(135deg, #2c3e50, #3498db); color: white; border-radius: 15px 15px 0 0 !important; }
        .btn-primary { background: linear-gradient(135deg, #3498db, #2c3e50); border: none; border-radius: 25px; }
        .role-badge { font-size: 0.7rem; padding: 0.25rem 0.5rem; }
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
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user me-2"></i> 
                        {{ session('usuario_nombre') }}
                        <span class="badge bg-{{ session('usuario_rol') == 'BIBLIOTECARIO' ? 'warning' : 'light' }} text-dark ms-2 role-badge">
                            {{ session('usuario_rol') }}
                        </span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><h6 class="dropdown-header">
                            <i class="fas fa-user-circle"></i> Mi Cuenta
                        </h6></li>
                        <li><span class="dropdown-item-text">
                            <small class="text-muted">{{ session('usuario_email') }}</small>
                        </span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="{{ route('logout') }}">
                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                        </a></li>
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
                        <!-- Dashboard - Todos -->
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                        
                        <!-- Libros - Todos pueden ver -->
                        <a class="nav-link {{ request()->routeIs('libros.*') ? 'active' : '' }}" href="{{ route('libros.index') }}">
                            <i class="fas fa-book"></i> Catálogo de Libros
                        </a>
                        
                        <!-- Separador -->
                        <hr class="my-2">
                        
                        @if(session('usuario_rol') == 'BIBLIOTECARIO')
                        <!-- Sección Administración - Solo Bibliotecarios -->
                        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-3 mb-1 text-muted">
                            <span><i class="fas fa-cogs"></i> Administración</span>
                        </h6>
                        
                        <a class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}" href="{{ route('usuarios.index') }}">
                            <i class="fas fa-users"></i> Gestión de Usuarios
                        </a>
                        
                        <a class="nav-link" href="{{ route('libros.create') }}">
                            <i class="fas fa-plus-circle"></i> Agregar Libro
                        </a>
                        
                        <a class="nav-link" href="{{ route('usuarios.create') }}">
                            <i class="fas fa-user-plus"></i> Nuevo Usuario
                        </a>
                        @else
                        <!-- Sección Usuario - Solo Usuarios normales -->
                        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-3 mb-1 text-muted">
                            <span><i class="fas fa-user"></i> Mi Cuenta</span>
                        </h6>
                        
                        <span class="nav-link disabled">
                            <i class="fas fa-cogs text-muted"></i> 
                            <span class="text-muted">Funciones administrativas</span>
                            <br><small class="text-muted">Solo para bibliotecarios</small>
                        </span>
                        @endif
                        
                        <!-- Separador -->
                        <hr class="my-2">
                        
                        <!-- Ayuda -->
                        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-3 mb-1 text-muted">
                            <span><i class="fas fa-question-circle"></i> Ayuda</span>
                        </h6>
                        
                        <a class="nav-link" href="#" onclick="mostrarAyuda()">
                            <i class="fas fa-info-circle"></i> ¿Cómo usar el sistema?
                        </a>
                    </nav>
                </div>
            </div>
            @endif

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="p-4">
                    <!-- Alertas de sistema -->
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

                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function mostrarAyuda() {
            const esAdmin = @json(session('usuario_rol') == 'BIBLIOTECARIO');
            
            let contenidoAyuda = `
                <div class="modal fade" id="modalAyuda" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-question-circle"></i> 
                                    Guía de uso - ${esAdmin ? 'Bibliotecario' : 'Usuario'}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
            `;
            
            if (esAdmin) {
                contenidoAyuda += `
                    <h6><i class="fas fa-cogs"></i> Funciones de Bibliotecario:</h6>
                    <ul>
                        <li><strong>Gestionar Libros:</strong> Crear, editar y eliminar libros del catálogo</li>
                        <li><strong>Gestionar Usuarios:</strong> Crear nuevos usuarios y administrar cuentas</li>
                        <li><strong>Ver Estadísticas:</strong> Acceder a métricas del sistema</li>
                        <li><strong>Búsquedas Avanzadas:</strong> Buscar libros y usuarios</li>
                    </ul>
                `;
            } else {
                contenidoAyuda += `
                    <h6><i class="fas fa-user"></i> Funciones de Usuario:</h6>
                    <ul>
                        <li><strong>Explorar Catálogo:</strong> Ver todos los libros disponibles</li>
                        <li><strong>Buscar Libros:</strong> Buscar por título, autor o categoría</li>
                        <li><strong>Ver Detalles:</strong> Consultar información detallada de cada libro</li>
                        <li><strong>Verificar Disponibilidad:</strong> Conocer qué libros están disponibles</li>
                    </ul>
                `;
            }
            
            contenidoAyuda += `
                                <hr>
                                <h6><i class="fas fa-keyboard"></i> Atajos de Teclado:</h6>
                                <ul>
                                    <li><kbd>Ctrl + /</kbd> - Enfocar búsqueda</li>
                                    <li><kbd>Escape</kbd> - Cerrar modales</li>
                                </ul>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Remover modal existente si existe
            const existingModal = document.getElementById('modalAyuda');
            if (existingModal) {
                existingModal.remove();
            }
            
            // Agregar nuevo modal
            document.body.insertAdjacentHTML('beforeend', contenidoAyuda);
            
            // Mostrar modal
            const modal = new bootstrap.Modal(document.getElementById('modalAyuda'));
            modal.show();
        }
        
        // Atajo de teclado para búsqueda
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === '/') {
                e.preventDefault();
                const searchInput = document.querySelector('input[name="q"]');
                if (searchInput) {
                    searchInput.focus();
                }
            }
        });
    </script>
</body>
</html>