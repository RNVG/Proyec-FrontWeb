@extends('layouts.app')

@section('title', 'Vehículos Disponibles')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Vehículos Disponibles por Rango</h3>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        {{-- Formulario de filtro --}}
        <div class="card card-primary card-outline mb-4">
            <div class="card-header">
                <h3 class="card-title">Seleccione el rango de fechas</h3>
            </div>
            <form method="GET" action="{{ route('reports.available') }}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Fecha Inicio</label>
                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Fecha Fin</label>
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" required>
                        </div>
                        <div class="col-md-4 mb-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Consultar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Resultados --}}
        @if(isset($results))
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title">Resultados</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 80px;">Imagen</th>
                                <th>Placa</th>
                                <th>Marca</th>
                                <th>Modelo</th>
                                <th>Año</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($results as $vehicle)
                                <tr>
                                    <td>
                                        @if(!empty($vehicle['image']))
                                            <img src="{{ $vehicle['image'] }}" alt="Vehículo" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <i class="fas fa-car fa-2x text-muted"></i>
                                        @endif
                                    </td>
                                    <td>{{ $vehicle['plate'] ?? 'N/A' }}</td>
                                    <td>{{ $vehicle['brand'] ?? 'N/A' }}</td>
                                    <td>{{ $vehicle['model'] ?? 'N/A' }}</td>
                                    <td>{{ $vehicle['year'] ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3">No se encontraron vehículos disponibles en ese rango.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection