<!doctype html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Flotilla | Mantenimientos</title>
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
                        <div class="col-sm-6"><h3 class="mb-0">Mantenimientos</h3></div>
                        <div class="col-sm-6 text-end">
                            @if(in_array(session('auth_user')['role_id'] ?? null, [1, 2]))
                                <a href="{{ route('maintenances.create') }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-plus-circle me-1"></i> Registrar mantenimiento
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
                                <h3 class="card-title">Listado de Mantenimientos</h3>
                                <form action="{{ route('maintenances.index') }}" method="GET">
                                    <select name="filter" class="form-select" onchange="this.form.submit()">
                                        <option value="" {{ ($filter ?? '') == '' ? 'selected' : '' }}>Activos</option>
                                        <option value="inactive" {{ ($filter ?? '') == 'inactive' ? 'selected' : '' }}>Inactivos</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                        @if(session('success'))<div class="alert alert-success rounded-0 mb-0">{{ session('success') }}</div>@endif
                        @if(session('error'))<div class="alert alert-danger rounded-0 mb-0">{{ session('error') }}</div>@endif
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th><th>Vehículo</th><th>Tipo</th><th>Inicio</th><th>Fin</th><th>Descripción</th><th>Costo</th><th>Estado</th><th class="text-end pe-3">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($maintenances as $item)
                                            @php $inactive = !empty($item['deleted_at']); @endphp
                                            <tr>
                                                <td>{{ $item['id'] }}</td>
                                                <td>{{ $item['vehicle']['plate'] ?? 'N/A' }}</td>
                                                <td>{{ $item['type'] }}</td>
                                                <td>{{ $item['start_date'] }}</td>
                                                <td>{{ $item['end_date'] ?? '—' }}</td>
                                                <td>{{ $item['description'] }}</td>
                                                <td>{{ number_format($item['cost'] ?? 0, 2) }}</td>
                                                <td>
                                                    @if($inactive)
                                                        <span class="badge rounded-pill text-bg-danger">Cancelado</span>
                                                    @else
                                                        @if($item['status'] == 'in_progress')
                                                            <span class="badge rounded-pill text-bg-warning">En Progreso</span>
                                                        @elseif($item['status'] == 'completed')
                                                            <span class="badge rounded-pill text-bg-success">Completado</span>
                                                        @else
                                                            <span class="badge rounded-pill text-bg-secondary">{{ $item['status'] }}</span>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td class="text-end pe-3">
                                                    <div class="d-flex justify-content-end gap-1">
                                                        @if($inactive)
                                                            <form action="{{ route('maintenances.restore', $item['id']) }}" method="POST" class="d-inline">
                                                                @csrf @method('PATCH')
                                                                <button class="btn btn-success btn-sm" title="Reactivar"><i class="bi bi-arrow-up-circle-fill"></i></button>
                                                            </form>
                                                        @else
                                                            @if($item['status'] == 'in_progress')
                                                                <form action="{{ route('maintenances.complete', $item['id']) }}" method="POST" class="d-inline">
                                                                    @csrf @method('PATCH')
                                                                    <button class="btn btn-outline-success btn-sm" title="Completar"><i class="bi bi-check-circle"></i></button>
                                                                </form>
                                                            @endif
                                                            <a href="{{ route('maintenances.edit', $item['id']) }}" class="btn btn-outline-warning btn-sm" title="Editar"><i class="bi bi-pencil"></i></a>
                                                            <form action="{{ route('maintenances.destroy', $item['id']) }}" method="POST" class="d-inline">
                                                                @csrf @method('DELETE')
                                                                <button class="btn btn-danger btn-sm" onclick="return confirm('¿Cancelar mantenimiento?')" title="Cancelar"><i class="bi bi-trash-fill"></i></button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="9" class="text-center py-5 text-muted"><i class="bi bi-cone-striped fs-1 d-block mb-2"></i>No hay mantenimientos registrados.</td></tr>
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