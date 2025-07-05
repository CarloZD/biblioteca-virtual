@extends('layouts.app')

@section('title', 'Detalle del Usuario')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-user"></i> Detalle del Usuario</h1>
    <div>
        <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Editar
        </a>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-circle"></i> {{ $usuario->nombre }}
                    <span class="badge bg-{{ $usuario->rol == 'BIBLIOTECARIO' ? 'primary' : 'secondary' }} ms-2">
                        {{ $usuario->rol }}
                    </span>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong><i class="fas fa-envelope"></i> Email:</strong> {{ $usuario->email }}</p>
                        <p><strong><i class="fas fa-user-tag"></i> Rol:</strong> 
                            <span class="badge bg-{{ $usuario->rol == 'BIBLIOTECARIO' ? 'primary' : 'secondary' }}">
                                {{ $usuario->rol }}
                            </span>
                        </p>
                        <p><strong><i class="fas fa-toggle-{{ $usuario->activo ? 'on text-success' : 'off text-danger' }}"></i> Estado:</strong>
                            <span class="badge bg-{{ $usuario->activo ? 'success' : 'danger' }}">
                                {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong><i class="fas fa-calendar-plus"></i> Registrado:</strong> 
                            {{ date('d/m/Y H:i', strtotime($usuario->created_at)) }}
                        </p>
                        <p><strong><i class="fas fa-id-badge"></i> ID Usuario:</strong> #{{ $usuario->id }}</p>
                        @if($usuario->rol == 'BIBLIOTECARIO')
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Permisos de Bibliotecario:</strong><br>
                            <small>Este usuario puede gestionar libros y otros usuarios del sistema.</small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-tools"></i> Acciones</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar Usuario
                    </a>
                    
                    @if($usuario->activo == 1 && $usuario->id != session('usuario_id'))
                    <form method="POST" action="{{ route('usuarios.destroy', $usuario->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" 
                                onclick="return confirm('¿Está seguro de desactivar este usuario?')">
                            <i class="fas fa-user-times"></i> Desactivar
                        </button>
                    </form>
                    @elseif($usuario->id == session('usuario_id'))
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <small>No puedes desactivar tu propia cuenta.</small>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Estadísticas del usuario -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-chart-bar"></i> Estadísticas</h6>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="text-primary">{{ date('j', strtotime($usuario->created_at)) }}</h4>
                            <small class="text-muted">Días desde registro</small>
                        </div>
                        <div class="col-6">
                            <h4 class="text-{{ $usuario->activo ? 'success' : 'danger' }}">
                                <i class="fas fa-{{ $usuario->activo ? 'check' : 'times' }}"></i>
                            </h4>
                            <small class="text-muted">Estado actual</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection