@extends('layouts.app')

@section('title', 'Registrar Mantenimiento')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Registrar Mantenimiento</h3></div>
            <div class="col-sm-6 text-end">
                <a href="{{ route('maintenances.index') }}" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Volver</a>
            </div>
        </div>
    </div>
</div>
<div class="app-content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">Datos del Mantenimiento</h3></div>
            @if($errors->any())
                <div class="alert alert-danger rounded-0 mb-0"><strong>Errores:</strong><ul class="mb-0 mt-2">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif
            <form action="{{ route('maintenances.store') }}" method="POST">
                @csrf
                {{-- Estado siempre inicia como "En progreso" --}}
                <input type="hidden" name="status" value="in_progress">

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Vehículo</label>
                            <select name="vehicle_id" class="form-select" required>
                                <option value="">-- Seleccione --</option>
                                @foreach($vehicles as $v)
                                    <option value="{{ $v['id'] }}" {{ old('vehicle_id') == $v['id'] ? 'selected' : '' }}>{{ $v['plate'] }} - {{ $v['brand'] }} {{ $v['model'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo</label>
                            <select name="type" class="form-select" required>
                                <option value="preventivo" {{ old('type') == 'preventivo' ? 'selected' : '' }}>Preventivo</option>
                                <option value="correctivo" {{ old('type') == 'correctivo' ? 'selected' : '' }}>Correctivo</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Fecha Inicio</label>
                            <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Fecha Fin (opcional)</label>
                            <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Costo</label>
                            <input type="number" step="0.01" name="cost" class="form-control" value="{{ old('cost') }}" required>
                        </div>
                        <div class="col-md-9 mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea name="description" class="form-control" rows="3" required>{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection