@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
    <div class="d-flex align-items-center">
        <span class="badge bg-{{ $usuario_rol == 'BIBLIOTECARIO' ? 'primary' : 'secondary' }} me-2">
            {{ $usuario_rol }}
        </span>
        <span class="text-muted">Bienvenido, {{ $usuario_nombre }}</span>
    </div>
</div>

<!-- Estadísticas -->
<div class="row">
    @if($usuario_rol == 'BIBLIOTECARIO')
    <div class="col-md-3">
        <div class="card bg-primary text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3>{{ $total_usuarios }}</h3>
                        <p class="mb-0">Usuarios Registrados</p>
                    </div>
                    <i class="fas fa-users fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="col-md-{{ $usuario_rol == 'BIBLIOTECARIO' ? '3' : '6' }}">
        <div class="card bg-warning text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3>{{ $total_libros }}</h3>
                        <p class="mb-0">Total de Libros</p>
                    </div>
                    <i class="fas fa-book fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-{{ $usuario_rol == 'BIBLIOTECARIO' ? '3' : '6' }}">
        <div class="card bg-success text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3>{{ $libros_disponibles }}</h3>
                        <p class="mb-0">Libros Disponibles</p>
                    </div>
                    <i class="fas fa-check-circle fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    @if($usuario_rol == 'BIBLIOTECARIO')
    <div class="col-md-3">
        <div class="card bg-info text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3>{{ $total_libros - $libros_disponibles }}</h3>
                        <p class="mb-0">Libros Prestados</p>
                    </div>
                    <i class="fas fa-hand-holding fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Acciones Rápidas -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt"></i> Acciones Rápidas
                    @if($usuario_rol == 'USUARIO')
                    <small class="text-muted ms-2">(Funciones disponibles para usuarios)</small>
                    @endif
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Acciones para todos los usuarios -->
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('libros.index') }}" class="btn btn-outline-success w-100 h-100 d-flex flex-column justify-content-center">
                            <i class="fas fa-book fa-2x mb-2"></i>
                            <span>Explorar Libros</span>
                            <small class="text-muted">Ver catálogo completo</small>
                        </a>
                    </div>

                    <div class="col-md-3 mb-3">
                        <form method="GET" action="{{ route('libros.buscar') }}" class="h-100">
                            <div class="input-group h-100">
                                <input type="text" class="form-control" name="q" placeholder="Buscar libros...">
                                <button class="btn btn-outline-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Acciones solo para BIBLIOTECARIOS -->
                    @if($usuario_rol == 'BIBLIOTECARIO')
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('libros.create') }}" class="btn btn-success w-100 h-100 d-flex flex-column justify-content-center">
                            <i class="fas fa-book-medical fa-2x mb-2"></i>
                            <span>Nuevo Libro</span>
                            <small class="text-muted">Agregar al catálogo</small>
                        </a>
                    </div>

                    <div class="col-md-3 mb-3">
                        <a href="{{ route('usuarios.index') }}" class="btn btn-outline-primary w-100 h-100 d-flex flex-column justify-content-center">
                            <i class="fas fa-users fa-2x mb-2"></i>
                            <span>Gestionar Usuarios</span>
                            <small class="text-muted">Ver y administrar</small>
                        </a>
                    </div>

                    <div class="col-md-3 mb-3">
                        <a href="{{ route('usuarios.create') }}" class="btn btn-primary w-100 h-100 d-flex flex-column justify-content-center">
                            <i class="fas fa-user-plus fa-2x mb-2"></i>
                            <span>Nuevo Usuario</span>
                            <small class="text-muted">Registrar bibliotecario</small>
                        </a>
                    </div>
                    @else
                    <!-- Mensaje informativo para usuarios normales -->
                    <div class="col-md-6 mb-3">
                        <div class="alert alert-info h-100 d-flex align-items-center">
                            <i class="fas fa-info-circle fa-2x me-3"></i>
                            <div>
                                <h6 class="mb-1">Cuenta de Usuario</h6>
                                <p class="mb-0">Puedes explorar y buscar libros en nuestro catálogo. Para funciones administrativas, contacta a un bibliotecario.</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Información adicional para usuarios -->
@if($usuario_rol == 'USUARIO')
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-lightbulb"></i> ¿Cómo usar la biblioteca virtual?</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center">
                            <i class="fas fa-search fa-3x text-primary mb-3"></i>
                            <h6>1. Busca</h6>
                            <p class="text-muted">Utiliza el buscador para encontrar libros por título, autor o categoría.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <i class="fas fa-eye fa-3x text-success mb-3"></i>
                            <h6>2. Explora</h6>
                            <p class="text-muted">Revisa los detalles de cada libro, incluyendo descripción y disponibilidad.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <i class="fas fa-bookmark fa-3x text-warning mb-3"></i>
                            <h6>3. Consulta</h6>
                            <p class="text-muted">Contacta al bibliotecario para solicitar préstamos de libros disponibles.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection