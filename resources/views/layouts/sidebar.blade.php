<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="{{ route('dashboard') }}" class="brand-link">
            <!--begin::Brand Image-->
            <img src="{{ asset('assets/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
                class="brand-image opacity-75 shadow" />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">Flotilla</span>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->

    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">

            <!--begin::Sidebar Menu-->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation"
                aria-label="Main navigation" data-accordion="false" id="navigation">

                <!-- Módulo Usuarios (Admin) -->
                @if(session('auth_user') && session('auth_user')['role_id'] == 1)
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-people-fill"></i>
                        <p>
                            Usuarios
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('users.register') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Registrar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Todos</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                <!-- Módulo Reportes (Solo Admin) -->
                @if(session('auth_user') && session('auth_user')['role_id'] == 1)
                <li class="nav-item">
                    <a href="{{ route('reports.index') }}" class="nav-link">
                        <i class="nav-icon bi bi-bar-chart-fill"></i>
                        <p>
                            Reportes
                        </p>
                    </a>
                </li>
                @endif

                <!-- Módulo Mantenimientos (Admin/Operador) -->
                @if(session('auth_user') && in_array(session('auth_user')['role_id'], [1, 2]))
                <li class="nav-item">
                    <a href="{{ route('maintenances.index') }}" class="nav-link">
                        <i class="nav-icon bi bi-cone-striped"></i>
                        <p>Mantenimientos</p>
                    </a>
                </li>
                @endif
                
                @if(session('auth_user') && session('auth_user')['role_id'] == 1)
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-people-fill"></i>
                        <p>
                            Rutas
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('routes.create') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Registrar nueva ruta</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('routes.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Ver todas la rutas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('routes.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Todos</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                @if(session('auth_user') && session('auth_user')['role_id'] == 1)
                    <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-people-fill"></i>
                        <p>
                            Vehiculos
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('vehicle.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Todos</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                @if(session('auth_user') && session('auth_user')['role_id'] == 2 || session('auth_user')['role_id'] == 1)
                    <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-people-fill"></i>
                        <p>
                            Solicitudes
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('requests.admin_index') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Todos</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                

                @if(session('auth_user') && session('auth_user')['role_id'] == 3)
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon bi bi-people-fill"></i>
                            <p>
                                Vehiculos
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('requests.index') }}" class="nav-link">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Ver vehiculos habilitados</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon bi bi-people-fill"></i>
                            <p>
                                Historial de solicitudes
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('requests.my_requests') }}" class="nav-link">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Ver historial de solicitudes</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                
                






                <!-- Módulo Vehículos (comentado) -->
                {{--
                @if(session('auth_user') && in_array(session('auth_user')['role_id'], [1, 2]))
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-truck"></i>
                        <p>
                            Vehículos
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('vehicles.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Todos</p>
                            </a>
                        </li>
                        @if(session('auth_user')['role_id'] == 1)
                        <li class="nav-item">
                            <a href="{{ route('vehicles.create') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Registrar</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                --}}

                <!-- Módulo Rutas (comentado) -->
                {{--
                @if(session('auth_user') && in_array(session('auth_user')['role_id'], [1, 2]))
                <!-- Módulo Rutas (Solo Operador) -->
                @if(session('auth_user') && session('auth_user')['role_id'] == 2)

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-map"></i>
                        <p>
                            Rutas
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('routes.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Todas</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                <!-- Módulo Vehículos (Admin) -->
                @if(session('auth_user') && session('auth_user')['role_id'] == 1)
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-truck"></i>
                        <p>
                            Vehículos
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('vehicle.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Todos</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                <!-- Módulo Solicitudes (Chofer/Operador) - Pendiente de activar -->
                <?php /*
                @if(session('auth_user'))
                    @php $role = session('auth_user')['role_id']; @endphp
                    @if($role == 3)
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon bi bi-calendar-check"></i>
                            <p>
                                Solicitudes
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('requests.create') }}" class="nav-link">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Nueva solicitud</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('requests.my') }}" class="nav-link">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Mis solicitudes</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @elseif($role == 2)
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon bi bi-inbox"></i>
                            <p>
                                Solicitudes
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('requests.pending') }}" class="nav-link">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Pendientes</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('requests.assign') }}" class="nav-link">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Asignación directa</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif
                @endif
                */ ?>

            </ul>
            <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>