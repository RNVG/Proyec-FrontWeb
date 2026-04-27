@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Reportes del Sistema</h3>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row g-4">
            {{-- Tarjeta Disponibilidad de Vehículos --}}
            <div class="col-md-4">
                <div class="card text-white bg-primary shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-car-front fs-1 mb-2"></i>
                        <h5 class="card-title">Disponibilidad de Vehículos</h5>
                        <p class="card-text">Consulte vehículos disponibles por rango de fechas.</p>
                        <a href="{{ route('reports.available') }}" class="btn btn-light stretched-link">Ver reporte</a>
                    </div>
                </div>
            </div>

            {{-- Tarjeta Uso de Flotilla --}}
            <div class="col-md-4">
                <div class="card text-white bg-success shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-graph-up-arrow fs-1 mb-2"></i>
                        <h5 class="card-title">Uso de Flotilla</h5>
                        <p class="card-text">Viajes por vehículo y kilómetros recorridos en un periodo.</p>
                        <a href="{{ route('reports.fleetUsage') }}" class="btn btn-light stretched-link">Ver reporte</a>
                    </div>
                </div>
            </div>

            {{-- Tarjeta Historial del Chofer --}}
            <div class="col-md-4">
                <div class="card text-white bg-info shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-person-lines-fill fs-1 mb-2"></i>
                        <h5 class="card-title">Historial del Chofer</h5>
                        <p class="card-text">Solicitudes y viajes de un chofer específico.</p>
                        <a href="{{ route('reports.driverHistory') }}" class="btn btn-light stretched-link">Ver reporte</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection