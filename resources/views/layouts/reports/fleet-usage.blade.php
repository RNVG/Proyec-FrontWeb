@extends('layouts.app')

@section('title', 'Uso de Flotilla')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Uso de Flotilla por Periodo</h3>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="card card-primary card-outline mb-4">
            <div class="card-header">
                <h3 class="card-title">Seleccione el rango de fechas</h3>
            </div>
            <form method="GET" action="{{ route('reports.fleetUsage') }}">
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
                                <th>Placa</th>
                                <th>Total Viajes</th>
                                <th>Total Kilómetros</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($results as $item)
                                <tr>
                                    <td>{{ $item['plate'] ?? 'N/A' }}</td>
                                    <td>{{ $item['total_trips'] ?? 0 }}</td>
                                    <td>{{ number_format($item['total_km'] ?? 0, 2) }} km</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-3">No se encontraron viajes en ese periodo.</td>
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