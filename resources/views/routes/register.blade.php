@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Registrar Nueva Ruta </h4>
        </div>
        <div class="card-body">
            <form action="{{ route('routes.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Nombre de la Ruta </label>
                        <input type="text" name="name" class="form-control" placeholder="Ej: Ruta Norte - Distribución" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Punto de Origen </label>
                        <input type="text" name="origin" class="form-control" placeholder="Ciudad o Dirección de salida" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Punto de Destino </label>
                        <input type="text" name="destination" class="form-control" placeholder="Ciudad o Dirección de llegada" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Distancia (KM) </label>
                        <input type="number" name="distance" step="0.1" class="form-control" placeholder="0.0" required>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Descripción / Observaciones </label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Detalles adicionales sobre la ruta..."></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('routes.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-success">Guardar Ruta</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection