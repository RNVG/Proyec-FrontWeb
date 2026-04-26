@extends('layouts.app')

@section('title', 'Editar Vehículo')

@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Editar Vehículo: {{ $vehicle['plate'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h3 class="card-title">Modificar datos de la unidad</h3>
                </div>
                <form action="{{ route('vehicle.update', $vehicle['id']) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') {{-- Indispensable para rutas de actualización --}}
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Placa</label>
                                <input type="text" name="plate" class="form-control" value="{{ $vehicle['plate'] }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Marca</label>
                                <input type="text" name="brand" class="form-control" value="{{ $vehicle['brand'] }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Modelo</label>
                                <input type="text" name="model" class="form-control" value="{{ $vehicle['model'] }}" required>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Año</label>
                                <input type="number" name="year" class="form-control" value="{{ $vehicle['year'] }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Capacidad</label>
                                <input type="number" name="capacity" class="form-control" value="{{ $vehicle['capacity'] }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Tipo</label>
                                <select name="type" class="form-select">
                                    <option value="sedan" {{ $vehicle['type'] == 'sedan' ? 'selected' : '' }}>Sedán</option>
                                    <option value="pickup" {{ $vehicle['type'] == 'pickup' ? 'selected' : '' }}>Pick-up</option>
                                    <option value="suv" {{ $vehicle['type'] == 'suv' ? 'selected' : '' }}>SUV</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Estado</label>
                                <select name="status" class="form-select">
                                    <option value="available" {{ $vehicle['status'] == 'available' ? 'selected' : '' }}>Disponible</option>
                                    <option value="maintenance" {{ $vehicle['status'] == 'maintenance' ? 'selected' : '' }}>Mantenimiento</option>
                                </select>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label d-block">Imagen Actual</label>
                                @if(!empty($vehicle['image']))
                                    <img src="{{ $vehicle['image'] }}" alt="Actual" class="img-thumbnail mb-2" style="width: 150px;">
                                @else
                                    <p class="text-muted">No hay imagen registrada.</p>
                                @endif
                                
                                <label class="form-label d-block">Subir Nueva Foto (Opcional)</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                                
                                {{-- Campo oculto para no perder la URL si no se sube una nueva --}}
                                <input type="hidden" name="old_image" value="{{ $vehicle['image'] }}">
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <a href="{{ route('vehicle.update', $vehicle['id']) }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-pencil-square"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection