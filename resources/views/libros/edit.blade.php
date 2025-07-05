@extends('layouts.app')

@section('title', 'Editar Libro')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-edit"></i> Editar Libro</h1>
    <a href="{{ route('libros.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Editar: {{ $libro->titulo }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('libros.update', $libro->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título *</label>
                                <input type="text" class="form-control @error('titulo') is-invalid @enderror" 
                                       id="titulo" name="titulo" value="{{ old('titulo', $libro->titulo) }}" required>
                                @error('titulo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="isbn" class="form-label">ISBN</label>
                                <input type="text" class="form-control" 
                                       id="isbn" name="isbn" value="{{ old('isbn', $libro->isbn) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="autor" class="form-label">Autor *</label>
                                <input type="text" class="form-control" 
                                       id="autor" name="autor" value="{{ old('autor', $libro->autor) }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="categoria" class="form-label">Categoría</label>
                                <select class="form-select" id="categoria" name="categoria">
                                    <option value="">Seleccionar</option>
                                    <option value="Literatura" {{ $libro->categoria == 'Literatura' ? 'selected' : '' }}>Literatura</option>
                                    <option value="Ciencia Ficción" {{ $libro->categoria == 'Ciencia Ficción' ? 'selected' : '' }}>Ciencia Ficción</option>
                                    <option value="Historia" {{ $libro->categoria == 'Historia' ? 'selected' : '' }}>Historia</option>
                                    <option value="Biografía" {{ $libro->categoria == 'Biografía' ? 'selected' : '' }}>Biografía</option>
                                    <option value="Infantil" {{ $libro->categoria == 'Infantil' ? 'selected' : '' }}>Infantil</option>
                                    <option value="Tecnología" {{ $libro->categoria == 'Tecnología' ? 'selected' : '' }}>Tecnología</option>
                                    <option value="Educación" {{ $libro->categoria == 'Educación' ? 'selected' : '' }}>Educación</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="fecha_publicacion" class="form-label">Fecha Publicación</label>
                                <input type="date" class="form-control" 
                                       id="fecha_publicacion" name="fecha_publicacion" 
                                       value="{{ old('fecha_publicacion', $libro->fecha_publicacion ? date('Y-m-d', strtotime($libro->fecha_publicacion)) : '') }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="4">{{ old('descripcion', $libro->descripcion) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="disponible" name="disponible" 
                                   {{ old('disponible', $libro->disponible) ? 'checked' : '' }}>
                            <label class="form-check-label" for="disponible">
                                Libro disponible
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('libros.index') }}" class="btn btn-secondary">Cancelar</a>
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