@extends('layouts.app')

@section('title', 'Editar Mantenimiento')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Editar Mantenimiento #{{ $maintenance['id'] }}</h3></div>
        </div>
    </div>
</div>
<div class="app-content">
    <div class="container-fluid">
        <div class="card card-warning card-outline">
            <div class="card-header"><h3 class="card-title">Modificar mantenimiento</h3></div>
            @if($errors->any())
                <div class="alert alert-danger rounded-0 mb-0"><strong>Errores:</strong><ul class="mb-0 mt-2">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif
            <form action="{{ route('maintenances.update', $maintenance['id']) }}" method="POST">
                @csrf @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Vehículo</label>
                            <select name="vehicle_id" class="form-select" required>
                                @foreach($vehicles as $v)
                                    <option value="{{ $v['id'] }}" {{ old('vehicle_id', $maintenance['vehicle_id']) == $v['id'] ? 'selected' : '' }}>{{ $v['plate'] }} - {{ $v['brand'] }} {{ $v['model'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo</label>
                            <select name="type" class="form-select" required>
                                <option value="preventivo" {{ old('type', $maintenance['type']) == 'preventivo' ? 'selected' : '' }}>Preventivo</option>
                                <option value="correctivo" {{ old('type', $maintenance['type']) == 'correctivo' ? 'selected' : '' }}>Correctivo</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Fecha Inicio</label>
                            <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $maintenance['start_date']) }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Fecha Fin</label>
                            <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $maintenance['end_date']) }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Costo</label>
                            <input type="number" step="0.01" name="cost" class="form-control" value="{{ old('cost', $maintenance['cost']) }}" required>
                        </div>
                        <div class="col-md-9 mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea name="description" class="form-control" rows="3" required>{{ old('description', $maintenance['description']) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('maintenances.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-warning"><i class="bi bi-pencil-square"></i> Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection