{{-- Vista de dashboard para Operador --}}
@extends('layouts.dashboard')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Panel de Operador</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard Operador</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        {{-- Mensaje de bienvenida --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-info">
                    <h5><i class="bi bi-person-badge me-2"></i>Bienvenido, {{ session('auth_user')['name'] ?? 'Operador' }}</h5>
                    <p class="mb-0">Desde aquí podrás gestionar solicitudes, asignar vehículos y administrar rutas.</p>
                </div>
            </div>
        </div>

        {{-- Tarjetas de acceso rápido --}}
        <div class="row g-4">
            {{-- Solicitudes pendientes --}}
            <div class="col-lg-3 col-md-6">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Solicitudes pendientes</h5>
                                <p class="card-text display-6">--</p>
                            </div>
                            <i class="bi bi-clock-history" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center bg-light text-dark">
                        <span>Ver detalles</span>
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </div>
            </div>

            {{-- Vehículos disponibles --}}
            <div class="col-lg-3 col-md-6">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Vehículos disponibles</h5>
                                <p class="card-text display-6">--</p>
                            </div>
                            <i class="bi bi-truck" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center bg-light text-dark">
                        <span>Ver flotilla</span>
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </div>
            </div>

            {{-- Asignaciones activas --}}
            <div class="col-lg-3 col-md-6">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Asignaciones activas</h5>
                                <p class="card-text display-6">--</p>
                            </div>
                            <i class="bi bi-calendar-check" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center bg-light text-dark">
                        <span>Gestionar</span>
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </div>
            </div>

            {{-- Rutas registradas --}}
            <div class="col-lg-3 col-md-6">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Rutas</h5>
                                <p class="card-text display-6">--</p>
                            </div>
                            <i class="bi bi-signpost" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center bg-light text-dark">
                        <span>Administrar</span>
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sección de próximas funciones --}}
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h5 class="card-title">Acciones rápidas</h5>
                    </div>
                    <div class="card-body">
                        <p>Desde este panel podrás:</p>
                        <ul>
                            <li>Ver y gestionar solicitudes de vehículos (aprobar/rechazar).</li>
                            <li>Realizar asignaciones directas.</li>
                            <li>Registrar entregas y devoluciones de vehículos.</li>
                            <li>Gestionar rutas y mantenimientos.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection