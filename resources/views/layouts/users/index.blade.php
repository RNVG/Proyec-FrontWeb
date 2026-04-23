<!doctype html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Flotilla | Usuarios</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />

    <meta name="title" content="Flotilla | Usuarios" />
    <meta name="author" content="ColorlibHQ" />
    <meta name="description" content="Listado de usuarios del sistema de flotilla." />
    <meta name="keywords" content="flotilla, usuarios, listado, adminlte, laravel" />

    <meta name="supported-color-schemes" content="light dark" />
    <link rel="preload" href="{{ asset('css/adminlte.css') }}" as="style" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" media="print"
        onload="this.media = 'all'" />

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
        crossorigin="anonymous" />

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
        crossorigin="anonymous" />

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
                            <h3 class="mb-0">Usuarios</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Listado de Usuarios</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <div class="card card-info card-outline mb-4">
                                <div class="card-header">
                                    <div class="row w-100 align-items-center">
                                        <div class="col-md-4 mb-2 mb-md-0">
                                            <h5 class="mb-0">Listado de usuarios</h5>
                                        </div>

                                        {{-- Filtro por estado (activos/inactivos) --}}
                                        <div class="col-md-4 mb-2 mb-md-0">
                                            <form method="GET" action="{{ route('users.index') }}">
                                                <select name="filter" class="form-select form-select-sm"
                                                    onchange="this.form.submit()">
                                                    <option value="" {{ empty($filter) ? 'selected' : '' }}>Todos</option>
                                                    <option value="inactive" {{ ($filter ?? '') === 'inactive' ? 'selected' : '' }}>
                                                        Inactivos
                                                    </option>
                                                </select>
                                            </form>
                                        </div>

                                        {{-- Botón para registrar nuevo usuario (solo admin) --}}
                                        <div class="col-md-4 text-end">
                                            <a href="{{ route('users.register') }}" class="btn btn-info btn-sm">
                                                <i class="bi bi-plus-circle me-1"></i>
                                                Registrar usuario
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                {{-- Mensajes flash --}}
                                @if (session('success'))
                                    <div class="alert alert-success rounded-0 mb-0">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if (session('error'))
                                    <div class="alert alert-danger rounded-0 mb-0">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger rounded-0 mb-0">
                                        <strong>Se encontraron errores:</strong>
                                        <ul class="mb-0 mt-2">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="card-body table-responsive p-0">
                                    <table class="table table-bordered table-hover align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th style="width: 50px;">ID</th>
                                                <th>Nombre</th>
                                                <th>Email</th>
                                                <th style="width: 120px;">Teléfono</th>
                                                <th style="width: 120px;">Rol</th>
                                                <th style="width: 120px;">Estado</th>
                                                <th style="width: 150px;" class="text-center">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($users as $user)
                                                @php
                                                    // Mapeo de roles actualizado: 1=Admin, 2=Operador, 3=Chofer
                                                    $roleId = $user['role_id'] ?? ($user['role']['id'] ?? null);
                                                    if ($roleId == 1) {
                                                        $roleName = 'Administrador';
                                                    } elseif ($roleId == 2) {
                                                        $roleName = 'Operador';
                                                    } elseif ($roleId == 3) {
                                                        $roleName = 'Chofer';
                                                    } else {
                                                        // Fallback al nombre que venga en la relación (si existe)
                                                        $roleName = $user['role']['role_name'] ?? 'Sin rol';
                                                    }

                                                    // Determinamos si el usuario está inactivo (borrado lógico)
                                                    $isDeleted = !empty($user['deleted_at']);
                                                @endphp
                                                <tr>
                                                    <td>{{ $user['id'] ?? 'N/A' }}</td>
                                                    <td>{{ $user['name'] ?? 'N/A' }}</td>
                                                    <td>{{ $user['email'] ?? 'N/A' }}</td>
                                                    <td>{{ $user['telephone'] ?? '—' }}</td>
                                                    <td>{{ $roleName }}</td>
                                                    <td>
                                                        {{-- Mostrar estado según filtro o campo deleted_at --}}
                                                        @if (($filter ?? '') === 'inactive' || $isDeleted)
                                                            <span class="badge text-bg-danger">Inactivo</span>
                                                        @else
                                                            <span class="badge text-bg-success">Activo</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="d-flex justify-content-center gap-2">
                                                            {{-- Si está inactivo, mostrar botón Reactivar --}}
                                                            @if (($filter ?? '') === 'inactive' || $isDeleted)
                                                                <form action="{{ route('users.restore', $user['id']) }}"
                                                                    method="POST"
                                                                    onsubmit="return confirm('¿Desea reactivar este usuario?');">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-outline-success"
                                                                        title="Reactivar usuario">
                                                                        <i class="bi bi-arrow-counterclockwise"></i>
                                                                    </button>
                                                                </form>
                                                            @else
                                                                {{-- Usuario activo: mostrar Editar e Inactivar --}}
                                                                <a href="{{ route('users.edit', $user['id']) }}"
                                                                    class="btn btn-sm btn-outline-primary"
                                                                    title="Editar usuario">
                                                                    <i class="bi bi-pencil-square"></i>
                                                                </a>

                                                                <form action="{{ route('users.destroy', $user['id']) }}"
                                                                    method="POST"
                                                                    onsubmit="return confirm('¿Desea inactivar este usuario? (borrado lógico)');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-outline-danger"
                                                                        title="Inactivar usuario">
                                                                        <i class="bi bi-trash"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted py-4">
                                                        No hay usuarios registrados.
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
            </div>
        </main>

        @include('layouts.footer')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
        crossorigin="anonymous"></script>

    <script src="{{ asset('js/adminlte.js') }}"></script>

    <script>
        const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
        const Default = {
            scrollbarTheme: 'os-theme-light',
            scrollbarAutoHide: 'leave',
            scrollbarClickScroll: true,
        };

        document.addEventListener('DOMContentLoaded', function() {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            const isMobile = window.innerWidth <= 992;

            if (
                sidebarWrapper &&
                OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined &&
                !isMobile
            ) {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
    </script>
</body>

</html>