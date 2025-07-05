@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-users"></i> Gestión de Usuarios</h1>
    <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
        <i class="fas fa-user-plus"></i> Nuevo Usuario
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Lista de Usuarios</h5>
    </div>
    <div class="card-body">
        @if(count($usuarios) > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Registrado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->id }}</td>
                        <td><strong>{{ $usuario->nombre }}</strong></td>
                        <td>{{ $usuario->email }}</td>
                        <td>
                            <span class="badge bg-{{ $usuario->rol == 'BIBLIOTECARIO' ? 'primary' : 'secondary' }}">
                                {{ $usuario->rol }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $usuario->activo == 1 ? 'success' : 'danger' }}">
                                {{ $usuario->activo == 1 ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td>{{ date('d/m/Y', strtotime($usuario->created_at)) }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('usuarios.show', $usuario->id) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($usuario->activo == 1)
                                <form method="POST" action="{{ route('usuarios.destroy', $usuario->id) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('¿Desactivar este usuario?')">
                                        <i class="fas fa-user-times"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-4">
            <i class="fas fa-users fa-3x text-muted mb-3"></i>
            <h5>No hay usuarios registrados</h5>
            <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Crear Primer Usuario
            </a>
        </div>
        @endif
    </div>
</div>
@endsection