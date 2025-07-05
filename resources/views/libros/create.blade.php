@extends('layouts.app')

@section('title', 'Nuevo Libro')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-plus"></i> Nuevo Libro</h1>
    <a href="{{ route('libros.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Información del Libro</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('libros.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título *</label>
                                <input type="text" class="form-control @error('titulo') is-invalid @enderror" 
                                       id="titulo" name="titulo" value="{{ old('titulo') }}" required>
                                @error('titulo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="isbn" class="form-label">ISBN</label>
                                <input type="text" class="form-control @error('isbn') is-invalid @enderror" 
                                       id="isbn" name="isbn" value="{{ old('isbn') }}">
                                @error('isbn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="autor" class="form-label">Autor *</label>
                                <input type="text" class="form-control @error('autor') is-invalid @enderror" 
                                       id="autor" name="autor" value="{{ old('autor') }}" required>
                                @error('autor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="categoria" class="form-label">Categoría</label>
                                <select class="form-select" id="categoria" name="categoria">
                                    <option value="">Seleccionar</option>
                                    <option value="Literatura">Literatura</option>
                                    <option value="Ciencia Ficción">Ciencia Ficción</option>
                                    <option value="Historia">Historia</option>
                                    <option value="Biografía">Biografía</option>
                                    <option value="Infantil">Infantil</option>
                                    <option value="Tecnología">Tecnología</option>
                                    <option value="Educación">Educación</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="fecha_publicacion" class="form-label">Fecha Publicación</label>
                                <input type="date" class="form-control" 
                                       id="fecha_publicacion" name="fecha_publicacion" value="{{ old('fecha_publicacion') }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="4">{{ old('descripcion') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('libros.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Crear Libro
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection