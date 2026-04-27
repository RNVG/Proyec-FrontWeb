<!doctype html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Flotilla | Rutas</title>
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
                        <div class="col-sm-6"><h3 class="mb-0">Rutas</h3></div>
                        <div class="col-sm-6 text-end">
                            @if(session('auth_user')['role_id'] == 2)
                                <a href="{{ route('routes.create') }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-plus-circle me-1"></i> Registrar ruta
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
                                <h3 class="card-title">Listado de Rutas</h3>
                                <form action="{{ route('routes.index') }}" method="GET">
                                    <select name="filter" class="form-select" onchange="this.form.submit()">
                                        <option value="" {{ ($filter ?? '') == '' ? 'selected' : '' }}>Activas</option>
                                        <option value="inactive" {{ ($filter ?? '') == 'inactive' ? 'selected' : '' }}>Inactivas</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                        @if (session('success')) <div class="alert alert-success rounded-0 mb-0">{{ session('success') }}</div> @endif
                        @if (session('error')) <div class="alert alert-danger rounded-0 mb-0">{{ session('error') }}</div> @endif
                        @if ($errors->any())
                            <div class="alert alert-danger rounded-0 mb-0"><strong>Se encontraron errores:</strong><ul class="mb-0 mt-2">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                        @endif
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr><th style="width:50px">ID</th><th>Nombre</th><th>Origen</th><th>Destino</th><th>Distancia (km)</th><th>Descripción</th><th>Estado</th><th style="width:150px" class="text-end pe-3">Acciones</th></tr>
                                    </thead>
                                    <tbody>
                                        @forelse($routes as $route)
                                            @php $inactive = !empty($route['deleted_at']); @endphp
                                            <tr>
                                                <td>{{ $route['id'] }}</td><td>{{ $route['name'] }}</td><td>{{ $route['origin'] }}</td><td>{{ $route['destination'] }}</td><td>{{ $route['distance'] ?? '—' }}</td><td>{{ $route['description'] ?? '—' }}</td>
                                                <td>
                                                    @if($inactive) <span class="badge rounded-pill text-bg-danger">Inactiva</span> @else <span class="badge rounded-pill text-bg-success">Activa</span> @endif
                                                </td>
                                                <td class="text-end pe-3">
                                                    <div class="d-flex justify-content-end gap-1">
                                                        @if($inactive)
                                                            @if(in_array(session('user_role') ?? session('auth_user')['role_id'] ?? null, [1, 2]))
                                                                <form action="{{ route('routes.restore', $route['id']) }}" method="POST" class="d-inline">@csrf @method('PATCH')<button class="btn btn-success btn-sm" title="Reactivar"><i class="bi bi-arrow-up-circle-fill"></i></button></form>
                                                            @endif
                                                        @else
                                                            @if(in_array(session('user_role') ?? session('auth_user')['role_id'] ?? null, [1, 2]))
                                                                <a href="{{ route('routes.edit', $route['id']) }}" class="btn btn-outline-warning btn-sm" title="Editar"><i class="bi bi-pencil"></i></a>
                                                                <form action="{{ route('routes.destroy', $route['id']) }}" method="POST" class="d-inline">@csrf @method('DELETE')<button class="btn btn-danger btn-sm" onclick="return confirm('¿Inactivar esta ruta?')" title="Inactivar"><i class="bi bi-trash-fill"></i></button></form>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="8" class="text-center py-5 text-muted"><i class="bi bi-signpost fs-1 d-block mb-2"></i>No hay rutas registradas.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if(isset($pagination) && $pagination['last_page'] > 1)
                        <div class="card-footer clearfix">
                            <ul class="pagination pagination-sm m-0 float-end">
                                @php $current = $pagination['current_page']; $last = $pagination['last_page']; @endphp
                                <li class="page-item {{ $current==1?'disabled':'' }}"><a class="page-link" href="?page={{ $current-1 }}&filter={{ $filter ?? '' }}">«</a></li>
                                @for($i=1;$i<=$last;$i++)<li class="page-item {{ $i==$current?'active':'' }}"><a class="page-link" href="?page={{ $i }}&filter={{ $filter ?? '' }}">{{ $i }}</a></li>@endfor
                                <li class="page-item {{ $current==$last?'disabled':'' }}"><a class="page-link" href="?page={{ $current+1 }}&filter={{ $filter ?? '' }}">»</a></li>
                            </ul>
                        </div>
                        @endif
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