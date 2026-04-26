@extends('layouts.app')

@section('title', 'Registrar Vehículo')

@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Registrar Nuevo Vehículo</h3>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ route('vehicle.index') }}" class="btn btn-secondary btn-sm">
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
                    <h3 class="card-title">Datos de la Unidad</h3>
                </div>
                
                {{-- IMPORTANTE: enctype para que el controlador reciba la imagen --}}
                <form action="{{ route('vehicle.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Placa</label>
                                <input type="text" name="plate" class="form-control" placeholder="ABC-123" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Marca</label>
                                <input type="text" name="brand" class="form-control" placeholder="Ej: Toyota" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Modelo</label>
                                <input type="text" name="model" class="form-control" placeholder="Ej: Hilux" required>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Año</label>
                                <input type="number" name="year" class="form-control" value="{{ date('Y') }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Capacidad</label>
                                <input type="number" name="capacity" class="form-control" placeholder="5">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Tipo</label>
                                <select name="type" class="form-select">
                                    <option value="sedan">Sedán</option>
                                    <option value="pickup">Pick-up</option>
                                    <option value="suv">SUV</option>
                                    <option value="van">Van</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Combustible</label>
                                <select name="fuel_type" class="form-select">
                                    <option value="gasoline">Gasolina</option>
                                    <option value="diesel">Diésel</option>
                                    <option value="electric">Eléctrico</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Estado</label>
                                <select name="status" class="form-select">
                                    <option value="available">Disponible</option>
                                    <option value="maintenance">Mantenimiento</option>
                                    <option value="out_of_service">Fuera de Servicio</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Fotografía</label>
                                <input type="file" name="image" class="form-control" accept="image/*" required>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Vehículo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection