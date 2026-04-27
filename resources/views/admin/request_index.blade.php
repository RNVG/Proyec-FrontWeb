@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">Gestión de Solicitudes </h2>
        <span class="badge bg-secondary">Perfil: Operador</span>
    </div>

    {{-- SECCIÓN A: SOLICITUDES PENDIENTES --}}
    <div class="card shadow-sm border-warning mb-5">
        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
            <span class="fw-bold"> Pendientes de Revisión</span>
            <span class="badge bg-dark">{{ $pending->count() }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Solicitante </th>
                            <th>Vehículo </th>
                            <th>Fechas </th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pending as $req)
                            <tr>
                                <td>
                                    <strong>{{ $req['user_name'] }}</strong><br>
                                    <small class="text-muted">ID Usuario: {{ $req['user_id'] }}</small>
                                </td>
                                <td>
                                    <span class="text-wrap">{{ $req['vehicle_info'] }}</span>
                                </td>
                                <td>
                                    <div class="small">
                                        <span class="text-success">Inicia:</span> {{ date('d/m/Y H:i', strtotime($req['start_date'])) }}<br>
                                        <span class="text-danger">Termina:</span> {{ date('d/m/Y H:i', strtotime($req['end_date'])) }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group shadow-sm">
                                        {{-- Botón Aprobar --}}
                                        <button type="button" 
                                                class="btn btn-sm btn-success" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#confirmModal" 
                                                data-id="{{ $req['id'] }}" 
                                                data-name="{{ $req['user_name'] }}" 
                                                data-action="approve">
                                            Aprobar
                                        </button>
                                        {{-- Botón Rechazar --}}
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#confirmModal" 
                                                data-id="{{ $req['id'] }}" 
                                                data-name="{{ $req['user_name'] }}" 
                                                data-action="reject">
                                            Rechazar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    No hay solicitudes esperando respuesta. ✨
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- SECCIÓN B: HISTORIAL --}}
    <div class="card shadow-sm border-secondary">
        <div class="card-header bg-secondary text-white fw-bold">
            Historial de Solicitudes
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Solicitante</th>
                            <th>Vehículo</th>
                            <th>Estado</th>
                            <th>Fecha Gestión</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($history as $h)
                            <tr>
                                <td>{{ $h['user_name'] }}</td>
                                <td><small>{{ $h['vehicle_info'] }}</small></td>
                                <td>
                                    <span class="badge {{ $h['status'] == 'approved' ? 'bg-success' : 'bg-danger' }}">
                                        {{ strtoupper($h['status']) }}
                                    </span>
                                </td>
                                <td>{{ date('d/m/Y', strtotime($h['updated_at'])) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-3">El historial está vacío.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL DE CONFIRMACIÓN --}}
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="confirmForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="action" id="modalAction">
            
            <div class="modal-content border-0 shadow">
                <div class="modal-header" id="modalHeader">
                    <h5 class="modal-title fw-bold text-white">Confirmar Decisión</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-4">
                    <p id="modalBodyText" class="fs-5 text-center mb-0"></p>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" id="confirmBtn" class="btn px-4">Confirmar</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const confirmModal = document.getElementById('confirmModal');
    confirmModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        
        const id = button.getAttribute('data-id');
        const action = button.getAttribute('data-action');
        const name = button.getAttribute('data-name');
        
        const form = document.getElementById('confirmForm');
        // Asegúrate de que esta ruta coincida con la de tu web.php
        form.action = `/admin/requests/${id}/update`;
        
        document.getElementById('modalAction').value = action;
        
        const isApprove = action === 'approve';
        document.getElementById('modalBodyText').innerHTML = 
            `¿Estás seguro de que deseas <strong>${isApprove ? 'APROBAR' : 'RECHAZAR'}</strong> la solicitud de <strong>${name}</strong>?`;
        
        const header = document.getElementById('modalHeader');
        const btn = document.getElementById('confirmBtn');
        
        if(isApprove) {
            header.className = 'modal-header bg-success';
            btn.className = 'btn btn-success';
            btn.innerText = 'Sí, Aprobar';
        } else {
            header.className = 'modal-header bg-danger';
            btn.className = 'btn btn-danger';
            btn.innerText = 'Sí, Rechazar';
        }
    });
</script>
@endpush

@endsection