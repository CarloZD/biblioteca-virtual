@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-user-edit"></i> Editar Usuario</h1>
    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Editar: {{ $usuario->nombre }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('usuarios.update', $usuario->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre Completo *</label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                               id="nombre" name="nombre" value="{{ old('nombre', $usuario->nombre) }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $usuario->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="rol" class="form-label">Rol *</label>
                        <select class="form-select @error('rol') is-invalid @enderror" id="rol" name="rol" required>
                            <option value="USUARIO" {{ $usuario->rol == 'USUARIO' ? 'selected' : '' }}>Usuario</option>
                            <option value="BIBLIOTECARIO" {{ $usuario->rol == 'BIBLIOTECARIO' ? 'selected' : '' }}>Bibliotecario</option>
                        </select>
                        @error('rol')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="activo" name="activo" 
                                   {{ old('activo', $usuario->activo) ? 'checked' : '' }}>
                            <label class="form-check-label" for="activo">
                                Usuario activo
                            </label>
                        </div>
                    </div>

                    <hr>
                    <h6>Cambiar Contraseña (opcional)</h6>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Nueva Contraseña</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password">
                        <div class="form-text">Dejar en blanco para mantener la contraseña actual</div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation">
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection