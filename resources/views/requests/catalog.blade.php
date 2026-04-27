@extends('layouts.app') 
@php
    $minDate = now()->format('Y-m-d\TH:i');
@endphp
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Catálogo de Vehículos Disponibles </h2>
    </div>

    <div class="row">
        @forelse($vehicles as $vehicle)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    @if($vehicle['image'])
                        <img src="{{ $vehicle['image'] }}" class="card-img-top" alt="Vehículo" style="height: 200px; object-fit: cover;">
                    @endif

                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title fw-bold">{{ $vehicle['brand'] }} {{ $vehicle['model'] }}</h5>
                            <span class="badge bg-success">Disponible</span>
                        </div>
                        <p class="text-muted small">Placa: <span class="fw-bold text-dark">{{ $vehicle['plate'] }}</span></p>
                        <hr>
                        <ul class="list-unstyled small">
                            <li><strong>Tipo:</strong> {{ $vehicle['type'] ?? 'N/A' }}</li>
                            <li><strong>Capacidad:</strong> {{ $vehicle['capacity'] ?? 'N/A' }} personas</li>
                        </ul>
                    </div>
                    
                    <div class="card-footer bg-white border-top-0">
                        @if(session('auth_user')['role_id'] == 3)
                            <button class="btn btn-primary w-100 fw-bold" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalReserva"
                                    onclick="configurarReserva({{ $vehicle['id'] }}, '{{ $vehicle['brand'] }} {{ $vehicle['model'] }}')">
                                Solicitar Vehículo 
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted fs-4">No hay vehículos disponibles para solicitud. 🚩</p>
            </div>
        @endforelse
    </div>
</div>

<div class="modal fade" id="modalReserva" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Nueva Solicitud de Asignación </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('requests.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="vehicle_id" id="modal_vehicle_id">
                    
                    <div class="alert alert-info">
                        Vas a solicitar el vehículo: <strong id="modal_vehicle_name"></strong>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Fecha de Inicio </label>
                            <input type="datetime-local" 
                                name="start_date" 
                                class="form-control" 
                                min="{{ $minDate }}" 
                                required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Fecha de Entrega </label>
                            <input type="datetime-local" 
                                name="end_date" 
                                class="form-control" 
                                min="{{ $minDate }}" 
                                required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Notas adicionales </label>
                            <textarea name="observations" class="form-control" rows="3" placeholder="¿Para qué necesitas el vehículo?"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Enviar Solicitud</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function configurarReserva(id, nombre) {
        document.getElementById('modal_vehicle_id').value = id;
        document.getElementById('modal_vehicle_name').innerText = nombre;
    }
</script>
@endsection