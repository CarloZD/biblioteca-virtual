@extends('layouts.app')

@section('title', 'Detalle del Usuario')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-user"></i> Detalle del Usuario</h1>
    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ $usuario->nombre }}</h5>
            </div>
            <div class="card-body">
                <p><strong>Email:</strong> {{ $usuario->email }}</p>
                <p><strong>Rol:</strong> {{ $usuario->rol }}</p>
                <p><strong>Estado:</strong>
                    <span class="badge bg-{{ $usuario->activo ? 'success' : 'danger' }}">
                        {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                    </span>
                </p>
                <p><strong>Registrado:</strong> {{ date('d/m/Y H:i', strtotime($usuario->created_at)) }}</p>
            </div>
        </div>
    </div>
</div>
@endsection