<!doctype html>
<html lang="es">
<!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Academy | Editar Usuario</title>

    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <!--end::Accessibility Meta Tags-->

    <!--begin::Primary Meta Tags-->
    <meta name="title" content="Academy | Editar Usuario" />
    <meta name="author" content="ColorlibHQ" />
    <meta name="description" content="Formulario para editar usuarios en el sistema Academy." />
    <meta name="keywords" content="academy, usuarios, editar usuario, adminlte, laravel, blade" />
    <!--end::Primary Meta Tags-->

    <!--begin::Accessibility Features-->
    <meta name="supported-color-schemes" content="light dark" />
    <link rel="preload" href="{{ asset('css/adminlte.css') }}" as="style" />
    <!--end::Accessibility Features-->

    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" media="print"
        onload="this.media = 'all'" />
    <!--end::Fonts-->

    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
        crossorigin="anonymous" />
    <!--end::Third Party Plugin(OverlayScrollbars)-->

    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
        crossorigin="anonymous" />
    <!--end::Third Party Plugin(Bootstrap Icons)-->

    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="{{ asset('css/adminlte.css') }}" />
    <!--end::Required Plugin(AdminLTE)-->
</head>
<!--end::Head-->

<!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
        <!--begin::Header-->
        @include('layouts.navbar')
        <!--end::Header-->

        <!--begin::Sidebar-->
        @include('layouts.sidebar')
        <!--end::Sidebar-->

        <!--begin::App Main-->
        <main class="app-main">
            <!--begin::App Content Header-->
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Editar</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuarios</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Editar Usuario</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::App Content Header-->

            <!--begin::App Content-->
            <div class="app-content">
                <div class="container-fluid">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <div class="card card-info card-outline mb-4">
                                <div class="card-header">
                                    <div class="card-title">Usuario</div>
                                </div>

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
                                        <strong>Se encontraron errores en el formulario:</strong>
                                        <ul class="mb-0 mt-2">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form action="{{ route('users.update', $user['id']) }}" method="POST" class="needs-validation"
                                    novalidate>
                                    @csrf
                                    @method('PUT')

                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="name" class="form-label">Nombre completo</label>
                                                <input type="text" name="name" id="name"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    value="{{ old('name', $user['name']) }}" required />
                                                <div class="invalid-feedback">Ingrese el nombre completo del usuario.</div>
                                                @error('name')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="email" class="form-label">Correo electrónico</label>
                                                <div class="input-group has-validation">
                                                    <span class="input-group-text" id="inputGroupPrepend">@</span>
                                                    <input type="email" name="email" id="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        value="{{ old('email', $user['email']) }}"
                                                        aria-describedby="inputGroupPrepend" required />
                                                    <div class="invalid-feedback">Ingrese un correo electrónico válido.</div>
                                                </div>
                                                @error('email')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="telephone" class="form-label">Teléfono</label>
                                                <input type="text" name="telephone" id="telephone"
                                                    class="form-control @error('telephone') is-invalid @enderror"
                                                    value="{{ old('telephone', $user['telephone']) }}" required />
                                                <div class="invalid-feedback">Ingrese el número telefónico.</div>
                                                @error('telephone')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="role_id" class="form-label">Rol</label>
                                                <select name="role_id" id="role_id"
                                                    class="form-select @error('role_id') is-invalid @enderror"
                                                    required>
                                                    <option value="">Seleccione un rol</option>
                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role['id'] }}"
                                                            {{ old('role_id', $user['role_id']) == $role['id'] ? 'selected' : '' }}>
                                                            {{ $role['rol_name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">Seleccione el rol del usuario.</div>
                                                @error('role_id')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="password" class="form-label">Nueva contraseña</label>
                                                <input type="password" name="password" id="password"
                                                    class="form-control @error('password') is-invalid @enderror" />
                                                <div class="form-text text-muted small">
                                                    Dejar en blanco si no desea cambiar la contraseña.
                                                </div>
                                                <div class="invalid-feedback">La contraseña debe tener al menos 8 caracteres.</div>
                                                @error('password')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="password_confirmation" class="form-label">Confirmar nueva contraseña</label>
                                                <input type="password" name="password_confirmation" id="password_confirmation"
                                                    class="form-control @error('password_confirmation') is-invalid @enderror" />
                                                <div class="invalid-feedback">Confirme la nueva contraseña.</div>
                                                @error('password_confirmation')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-footer d-flex justify-content-between">
                                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                            <i class="bi bi-arrow-left me-1"></i>
                                            Volver
                                        </a>

                                        <button class="btn btn-info" type="submit">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Actualizar usuario
                                        </button>
                                    </div>
                                </form>

                                <script>
                                    (() => {
                                        'use strict';

                                        const forms = document.querySelectorAll('.needs-validation');

                                        Array.from(forms).forEach((form) => {
                                            form.addEventListener(
                                                'submit',
                                                (event) => {
                                                    if (!form.checkValidity()) {
                                                        event.preventDefault();
                                                        event.stopPropagation();
                                                    }

                                                    form.classList.add('was-validated');
                                                },
                                                false,
                                            );
                                        });
                                    })();
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::App Content-->
        </main>
        <!--end::App Main-->

        <!--begin::Footer-->
        @include('layouts.footer')
        <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->

    <!--begin::Script-->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>

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
    <!--end::Script-->
</body>
<!--end::Body-->

</html>