@extends('layouts.app')

@section('title', 'Gestión de Libros')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-book"></i> Gestión de Libros</h1>
    <a href="{{ route('libros.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nuevo Libro
    </a>
</div>

<!-- Búsqueda -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('libros.buscar') }}">
            <div class="row">
                <div class="col-md-10">
                    <input type="text" class="form-control" name="q" 
                           placeholder="Buscar por título, autor o categoría..." 
                           value="{{ request('q') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Lista de Libros</h5>
    </div>
    <div class="card-body">
        @if(count($libros) > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>Categoría</th>
                        <th>Disponibilidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($libros as $libro)
                    <tr>
                        <td>{{ $libro->id }}</td>
                        <td><strong>{{ $libro->titulo }}</strong></td>
                        <td>{{ $libro->autor }}</td>
                        <td>{{ $libro->categoria ?? 'Sin categoría' }}</td>
                        <td>
                            <span class="badge bg-{{ $libro->disponible == 1 ? 'success' : 'danger' }}">
                                {{ $libro->disponible == 1 ? 'Disponible' : 'No Disponible' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('libros.show', $libro->id) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('libros.edit', $libro->id) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if(session('usuario_rol') == 'BIBLIOTECARIO')
                                <form method="POST" action="{{ route('libros.destroy', $libro->id) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('¿Eliminar este libro?')">
                                        <i class="fas fa-trash"></i>
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
            <i class="fas fa-book fa-3x text-muted mb-3"></i>
            <h5>No se encontraron libros</h5>
            <a href="{{ route('libros.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Agregar Primer Libro
            </a>
        </div>
        @endif
    </div>
</div>
@endsection