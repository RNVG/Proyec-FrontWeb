<!doctype html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Flotilla | Vehículos</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/adminlte.css') }}" />
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        @include('layouts.navbar')
        @include('layouts.sidebar')

        <main class="app-main">
            <div class="app-content-header">
                
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Vehículos Disponibles</h3>
                        </div>
                        {{-- Botón Registrar: Solo para Administradores (Rol 1) --}}
                        <div class="col-sm-6 text-end">
                            @if(session('auth_user') && session('auth_user')['role_id'] == 1)
                                <a href="{{ route('vehicle.create') }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-plus-circle me-1"></i> Registrar vehículo
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="app-content">
                <div class="container-fluid">
                    <div class="card shadow-sm">
                        <div class="card-header border-transparent">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="card-title">Listado de Flotilla</h3>
                                {{-- Filtros: Ocultos para Choferes --}}
                                @if(session('user_role') != 3)
                                   <form action="{{ route('vehicle.index') }}" method="GET">
                                    <select name="status_filter" class="form-select" onchange="this.form.submit()">
                                        <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Todos</option>
                                        <option value="available" {{ $status == 'available' ? 'selected' : '' }}>Habilitados</option>
                                        <option value="unavailable" {{ $status == 'unavailable' ? 'selected' : '' }}>No habilitados</option>
                                        <option value="under_maintenance" {{ $status == 'under_maintenance' ? 'selected' : '' }}>En Mantenimiento</option>
                                    </select>
                                </form>
                                @endif
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 80px" class="ps-3">Imagen</th>
                                            <th>Placa</th>
                                            <th>Marca / Modelo</th>
                                            <th>Año</th>
                                            <th>Tipo</th>
                                            <th>Capacidad</th>
                                            <th>Estado</th>
                                            <th style="width: 150px" class="text-end pe-3">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($vehicles as $vehicle)
                                            <tr>
                                                <td class="ps-3">
                                                    @if(!empty($vehicle['image']))
                                                        <img src="{{ $vehicle['image'] }}" alt="Vehículo" class="rounded shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light d-flex align-items-center justify-content-center rounded" style="width: 50px; height: 50px;">
                                                            <i class="fas fa-car text-muted"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td><span class="badge text-bg-dark shadow-sm">{{ $vehicle['plate'] }}</span></td>
                                                <td>
                                                    <strong>{{ $vehicle['brand'] }}</strong><br>
                                                    <small class="text-muted">{{ $vehicle['model'] }}</small>
                                                </td>
                                                <td>{{ $vehicle['year'] }}</td>
                                                <td>{{ ucfirst($vehicle['type'] ?? 'N/A') }}</td>
                                                <td>{{ $vehicle['capacity'] ?? 0 }} pers.</td>
                                                <td>
                                                    @if($vehicle['deleted_at'])
                                                        <span class="badge rounded-pill text-bg-danger">Eliminado</span>
                                                    @elseif($vehicle['status'] == 'available')
                                                        <span class="badge rounded-pill text-bg-success">Habilitado</span>
                                                    @elseif($vehicle['status'] == 'under_maintenance')
                                                        <span class="badge rounded-pill text-bg-warning">Mantenimiento</span>
                                                    @else
                                                        <span class="badge rounded-pill text-bg-secondary">Inhabilitado</span>
                                                    @endif
                                                </td>
                                                <td class="text-end pe-3">
                                                    <div class="d-flex justify-content-end gap-1">
                                                        <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalDetalle{{ $vehicle['id'] }}" title="Ver Detalles">
                                                            <i class="bi bi-eye"></i>
                                                        </button>

                                                        @if($vehicle['deleted_at'])
                                                            @if(session('user_role') != 3)
                                                                <form action="{{ route('vehicle.restore', $vehicle['id']) }}" method="POST" class="d-inline">
                                                                    @csrf @method('PATCH')
                                                                    <button type="submit" class="btn btn-success btn-sm" title="Activar">
                                                                        <i class="bi bi-arrow-up-circle-fill"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        @else
                                                            {{-- Acciones solo para Admin/Gestor --}}
                                                            @if(session('user_role') != 3)
                                                                <a href="{{ route('vehicle.edit', $vehicle['id']) }}" class="btn btn-outline-warning btn-sm" title="Editar">
                                                                    <i class="bi bi-pencil"></i>
                                                                </a>
                                                                <form action="{{ route('vehicle.destroy', $vehicle['id']) }}" method="POST" class="d-inline">
                                                                    @csrf @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Enviar a la papelera?')" title="Eliminar">
                                                                        <i class="bi bi-trash-fill"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>

                                            {{-- MODAL DETALLE --}}
                                            <div class="modal fade" id="modalDetalle{{ $vehicle['id'] }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-dark text-white">
                                                            <h5 class="modal-title"><i class="bi bi-info-circle me-2"></i>Detalles del Vehículo</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row align-items-center">
                                                                <div class="col-md-6">
                                                                    <img src="{{ $vehicle['image'] ?? asset('img/no-car.png') }}" class="img-fluid rounded shadow" alt="Auto" style="width: 100%; max-height: 300px; object-fit: cover;">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <h4 class="fw-bold text-primary">{{ $vehicle['brand'] }} {{ $vehicle['model'] }}</h4>
                                                                    <p class="text-muted">Año de fabricación: {{ $vehicle['year'] }}</p>
                                                                    <hr>
                                                                    <dl class="row">
                                                                        <dt class="col-sm-4">Placa:</dt> <dd class="col-sm-8">{{ $vehicle['plate'] }}</dd>
                                                                        <dt class="col-sm-4">Tipo:</dt> <dd class="col-sm-8">{{ ucfirst($vehicle['type'] ?? 'N/A') }}</dd>
                                                                        <dt class="col-sm-4">Capacidad:</dt> <dd class="col-sm-8">{{ $vehicle['capacity'] ?? 0 }} pasajeros</dd>
                                                                        <dt class="col-sm-4">Motor:</dt> <dd class="col-sm-8">{{ ucfirst($vehicle['fuel_type'] ?? 'N/A') }}</dd>
                                                                    </dl>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer bg-light">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                            @if(session('user_role') == 3 && $vehicle['status'] == 'active' && !$vehicle['deleted_at'])
                                                                <a href="{{ route('reservation.create', ['vehicle_id' => $vehicle['id']]) }}" class="btn btn-primary shadow">
                                                                    <i class="bi bi-calendar-check me-1"></i> Reservar este vehículo
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-5 text-muted">
                                                    <i class="bi bi-car-front fs-1 d-block mb-2"></i>
                                                    No se encontraron vehículos disponibles en esta categoría.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        @include('layouts.footer')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/adminlte.js') }}"></script>
</body>
</html>