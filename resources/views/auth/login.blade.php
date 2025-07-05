@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <div class="card-header text-center">
                    <h4><i class="fas fa-book"></i> Biblioteca Virtual</h4>
                    <p class="mb-0">Iniciar Sesión</p>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i> Correo Electrónico
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock"></i> Contraseña
                            </label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                            </button>
                        </div>
                    </form>

                    <hr>
                    <div class="text-center">
                        <small class="text-muted">
                            <strong>Usuarios de prueba:</strong><br>
                            <strong>Admin:</strong> admin@biblioteca.com<br>
                            <strong>Usuario:</strong> usuario@biblioteca.com<br>
                            <strong>Contraseña:</strong> password
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection