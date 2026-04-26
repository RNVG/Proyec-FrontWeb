<!doctype html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Flotilla | Vehículos</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    {{-- Font Awesome para los iconos fas fa-car, etc --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- Asegúrate de que este archivo exista en public/css/adminlte.css --}}
    <link rel="stylesheet" href="{{ asset('css/adminlte.css') }}" />
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        @include('layouts.navbar')
        @include('layouts.sidebar')

        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Vehículos Disponibles</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-header">
                                <form action="{{ route('vehicle.index') }}" method="GET" id="formFilter" class="row g-2">
                                    <div class="col-md-4">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-light"><i class="bi bi-filter"></i></span>
                                            <select name="status_filter" class="form-select" onchange="this.form.submit()">
                                                <option value="active" {{ $status == 'active' ? 'selected' : '' }}>Ver Activos</option>
                                                <option value="inactive" {{ $status == 'inactive' ? 'selected' : '' }}>Ver: Ver Inactivos</option>
                                                <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Ver Todos</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <h3 class="card-title">Listado de Flotilla</h3>
                            @if(session('auth_user') && session('auth_user')['role_id'] == 1):
                                <div class="col-md-4 text-end">
                                <a href="{{ route('vehicle.create') }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-plus-circle me-1"></i>
                                    Registrar vehículo
                                </a>
                                </div>
                            @endif
                        </div>

                        <div class="card-body p-0"> {{-- p-0 para que la tabla llegue a los bordes --}}
                            <div class="table-responsive">
                                <table class="table table-striped align-middle">
                                    <thead>
                                        <tr>
                                            <th style="width: 80px">Imagen</th>
                                            <th>Placa</th>
                                            <th>Marca / Modelo</th>
                                            <th>Año</th>
                                            <th>Tipo</th>
                                            <th>Capacidad</th>
                                            <th>Estado</th>
                                            <th style="width: 150px">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($vehicles as $vehicle)
                                        <tr>
                                            <td>
                                                @if(!empty($vehicle['image']))
                                                    <img src="{{ $vehicle['image'] }}" 
                                                         alt="Vehículo" 
                                                         class="rounded" 
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center rounded" style="width: 50px; height: 50px;">
                                                        <i class="fas fa-car text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td><span class="badge text-bg-dark">{{ $vehicle['plate'] }}</span></td>
                                            <td>
                                                <strong>{{ $vehicle['brand'] }}</strong><br>
                                                <small class="text-muted">{{ $vehicle['model'] }}</small>
                                            </td>
                                            <td>{{ $vehicle['year'] }}</td>
                                            <td>{{ ucfirst($vehicle['type'] ?? 'N/A') }}</td>
                                            <td>{{ $vehicle['capacity'] ?? 0 }} pers.</td>
                                            <td>
                                                {{-- Usamos @if($vehicle['deleted_at']) para verificar si tiene fecha de borrado --}}
                                                @if(isset($vehicle['deleted_at']) && $vehicle['deleted_at'] !== null)
                                                    <span class="badge rounded-pill text-bg-danger">
                                                        <i class="bi bi-x-circle me-1"></i> Inactivo
                                                    </span>
                                                @else
                                                    <span class="badge rounded-pill text-bg-success">
                                                        <i class="bi bi-check-circle me-1"></i> Activo
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('vehicle.show', $vehicle['id']) }}" class="btn btn-outline-info btn-sm" title="Ver Detalles">
                                                    <i class="bi bi-eye"></i>
                                                </a>

                                                @if($vehicle['deleted_at'])
                                                    {{-- BOTÓN CUANDO EL VEHÍCULO ESTÁ INACTIVO --}}
                                                    <form action="{{ route('vehicle.restore', $vehicle['id']) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-success btn-sm" title="Activar Vehículo">
                                                            <i class="bi bi-arrow-up-circle-fill me-1"></i> Activar
                                                        </button>
                                                    </form>
                                                @else
                                                    {{-- BOTONES CUANDO EL VEHÍCULO ESTÁ ACTIVO --}}
                                                    <a href="{{ route('vehicle.edit', $vehicle['id']) }}" class="btn btn-outline-warning btn-sm" title="Editar">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>

                                                    <form action="{{ route('vehicle.destroy', $vehicle['id']) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                                onclick="return confirm('¿Seguro que quieres enviar este vehículo a la papelera?')" 
                                                                title="Desactivar">
                                                            <i class="bi bi-trash-fill"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">No se encontraron vehículos.</td>
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