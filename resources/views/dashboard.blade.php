@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
    <span class="text-muted">Bienvenido, {{ $usuario_nombre }}</span>
</div>

<div class="row">
    <!-- Estadísticas -->
    @if($usuario_rol == 'BIBLIOTECARIO')
    <div class="col-md-4">
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

    <div class="col-md-4">
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

    <div class="col-md-4">
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
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-line"></i> Acciones Rápidas</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @if($usuario_rol == 'BIBLIOTECARIO')
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('usuarios.create') }}" class="btn btn-primary w-100">
                            <i class="fas fa-user-plus"></i><br>
                            Nuevo Usuario
                        </a>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('usuarios.index') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-users"></i><br>
                            Ver Usuarios
                        </a>
                    </div>
                    @endif

                    <div class="col-md-3 mb-3">
                        <a href="{{ route('libros.create') }}" class="btn btn-success w-100">
                            <i class="fas fa-book-medical"></i><br>
                            Nuevo Libro
                        </a>
                    </div>

                    <div class="col-md-3 mb-3">
                        <a href="{{ route('libros.index') }}" class="btn btn-outline-success w-100">
                            <i class="fas fa-book"></i><br>
                            Ver Libros
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection