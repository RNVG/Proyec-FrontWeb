{{-- Vista de dashboard para Chofer --}}
@extends('layouts.dashboard')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Panel de Chofer</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard Chofer</li>
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
                <div class="alert alert-success">
                    <h5><i class="bi bi-person-circle me-2"></i>¡Hola, {{ session('auth_user')['name'] ?? 'Chofer' }}!</h5>
                    <p class="mb-0">Aquí puedes ver vehículos disponibles, hacer solicitudes y consultar tu historial.</p>
                </div>
            </div>
        </div>

        {{-- Tarjetas de acceso rápido --}}
        <div class="row g-4">
            {{-- Vehículos disponibles --}}
            <div class="col-lg-4 col-md-6">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Vehículos disponibles</h5>
                                <p class="card-text display-6">--</p>
                            </div>
                            <i class="bi bi-car-front" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center bg-light text-dark">
                        <span>Ver catálogo</span>
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </div>
            </div>

            {{-- Mis solicitudes --}}
            <div class="col-lg-4 col-md-6">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Mis solicitudes</h5>
                                <p class="card-text display-6">--</p>
                            </div>
                            <i class="bi bi-list-check" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center bg-light text-dark">
                        <span>Ver historial</span>
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </div>
            </div>

            {{-- Viajes realizados --}}
            <div class="col-lg-4 col-md-6">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Viajes este mes</h5>
                                <p class="card-text display-6">--</p>
                            </div>
                            <i class="bi bi-map" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center bg-light text-dark">
                        <span>Ver viajes</span>
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sección informativa --}}
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h5 class="card-title">Próximas funciones</h5>
                    </div>
                    <div class="card-body">
                        <ul>
                            <li>Solicitar un vehículo para un rango de fechas.</li>
                            <li>Cancelar solicitudes propias.</li>
                            <li>Consultar el historial de tus viajes.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection