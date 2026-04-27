@extends('layouts.app')

@section('title', 'Registrar Ruta')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Registrar Nueva Ruta</h3>
            </div>
            <div class="col-sm-6 text-end">
                <a href="{{ route('routes.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Volver al listado
                </a>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Datos de la Ruta</h3>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger rounded-0 mb-0">
                    <strong>Se encontraron errores:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('routes.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre de la ruta</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Origen</label>
                            <input type="text" name="origin" class="form-control" value="{{ old('origin') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Destino</label>
                            <input type="text" name="destination" class="form-control" value="{{ old('destination') }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Distancia estimada (km)</label>
                            <input type="number" step="any" name="distance" class="form-control" value="{{ old('distance') }}">
                        </div>
                        <div class="col-md-9 mb-3">
                            <label class="form-label">Descripción</label>
                            <input type="text" name="description" class="form-control" value="{{ old('description') }}">
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Guardar Ruta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection