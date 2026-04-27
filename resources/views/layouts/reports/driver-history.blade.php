@extends('layouts.app')

@section('title', 'Historial del Chofer')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Historial del Chofer</h3>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="card card-primary card-outline mb-4">
            <div class="card-header">
                <h3 class="card-title">Seleccione un chofer</h3>
            </div>
            <form method="GET" action="{{ route('reports.driverHistory') }}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Chofer</label>
                            <select name="driver_id" class="form-select" required>
                                <option value="">-- Seleccione --</option>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver['id'] }}" {{ request('driver_id') == $driver['id'] ? 'selected' : '' }}>
                                        {{ $driver['name'] }} ({{ $driver['email'] }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Ver Historial</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        @if(isset($results))
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title">Historial</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tipo</th>
                                <th>Estado</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Vehículo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($results as $row)
                                @php
                                    // Determinar si es viaje o solicitud
                                    $type = !empty($row['trip_id']) ? 'Viaje' : 'Solicitud';
                                    $status = !empty($row['trip_id']) ? ($row['trip_status'] ?? 'N/A') : ($row['request_status'] ?? 'N/A');
                                    $start = !empty($row['trip_id']) ? ($row['departure_time'] ?? '—') : ($row['start_date'] ?? '—');
                                    $end = !empty($row['trip_id']) ? ($row['return_time'] ?? '—') : ($row['end_date'] ?? '—');
                                    $vehicle = !empty($row['trip_id']) ? ($row['trip_vehicle'] ?? '—') : ($row['request_vehicle'] ?? '—');
                                @endphp
                                <tr>
                                    <td>{{ $type }}</td>
                                    <td>{{ $status }}</td>
                                    <td>{{ $start }}</td>
                                    <td>{{ $end }}</td>
                                    <td>{{ $vehicle }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3">El chofer no tiene viajes ni solicitudes registrados.</td>
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