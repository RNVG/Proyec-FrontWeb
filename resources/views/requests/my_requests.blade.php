@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Mis Solicitudes de Vehículos 📑</h2>
        <a href="{{ route('requests.catalog') }}" class="btn btn-primary">Nueva Solicitud +</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">Vehículo</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold">{{ $req['vehicle']['brand'] ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $req['vehicle']['plate'] ?? 'Sin placa' }}</small>
                                </td>
                                <td>{{ date('d/m/Y H:i', strtotime($req['start_date'])) }}</td>
                                <td>{{ date('d/m/Y H:i', strtotime($req['end_date'])) }}</td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'pending' => 'bg-warning text-dark',
                                            'approved' => 'bg-success',
                                            'rejected' => 'bg-danger'
                                        ][$req['status']] ?? 'bg-secondary';
                                    @endphp
                                    <span class="badge {{ $statusClass }} px-3 py-2 text-uppercase">
                                        {{ $req['status'] }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    {{-- Solo se puede cancelar si está pendiente o aprobada --}}
                                    @if($req['status'] != 'rejected')
                                        <form action="{{ route('requests.cancel', $req['id']) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Seguro que quieres cancelar esta solicitud?')">
                                                Cancelar 
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted small">No disponible</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    No tienes solicitudes registradas aún.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection