@extends('layouts.app')

@section('title', 'Editar Ruta')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Editar Ruta: {{ $route['name'] }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="card card-warning card-outline">
            <div class="card-header">
                <h3 class="card-title">Modificar datos de la ruta</h3>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('routes.update', $route['id']) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre de la ruta</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $route['name']) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Origen</label>
                            <input type="text" name="origin" class="form-control" value="{{ old('origin', $route['origin']) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Destino</label>
                            <input type="text" name="destination" class="form-control" value="{{ old('destination', $route['destination']) }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Distancia estimada (km)</label>
                            <input type="number" step="any" name="distance" class="form-control" value="{{ old('distance', $route['distance'] ?? '') }}">
                        </div>
                        <div class="col-md-9 mb-3">
                            <label class="form-label">Descripción</label>
                            <input type="text" name="description" class="form-control" value="{{ old('description', $route['description'] ?? '') }}">
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('routes.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-pencil-square"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection