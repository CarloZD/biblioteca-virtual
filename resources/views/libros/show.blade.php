@extends('layouts.app')

@section('title', 'Detalle del Libro')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-book"></i> Detalle del Libro</h1>
    <div>
        <a href="{{ route('libros.edit', $libro->id) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Editar
        </a>
        <a href="{{ route('libros.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ $libro->titulo }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Autor:</strong> {{ $libro->autor }}</p>
                        <p><strong>ISBN:</strong> {{ $libro->isbn ?? 'No especificado' }}</p>
                        <p><strong>Categoría:</strong> {{ $libro->categoria ?? 'Sin categoría' }}</p>
                        <p><strong>Fecha de Publicación:</strong> 
                            {{ $libro->fecha_publicacion ? date('d/m/Y', strtotime($libro->fecha_publicacion)) : 'No especificada' }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Estado:</strong> 
                            <span class="badge bg-{{ $libro->disponible ? 'success' : 'danger' }}">
                                {{ $libro->disponible ? 'Disponible' : 'No Disponible' }}
                            </span>
                        </p>
                        <p><strong>Registrado:</strong> {{ date('d/m/Y H:i', strtotime($libro->created_at)) }}</p>
                    </div>
                </div>
                
                @if($libro->descripcion)
                <hr>
                <h6>Descripción:</h6>
                <p>{{ $libro->descripcion }}</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Acciones</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('libros.edit', $libro->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar Libro
                    </a>
                    
                    @if(session('usuario_rol') == 'BIBLIOTECARIO')
                    <form method="POST" action="{{ route('libros.destroy', $libro->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" 
                                onclick="return confirm('¿Está seguro de eliminar este libro?')">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection