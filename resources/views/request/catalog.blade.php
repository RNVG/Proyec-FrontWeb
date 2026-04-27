{{-- 1. Extendemos del layout principal --}}
@extends('layouts.app') 

{{-- 2. Inyectamos el contenido en la sección correspondiente --}}

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Catálogo de Vehículos Disponibles 🚗</h2>
    </div>

    {{-- Aquí iría la cuadrícula de tarjetas (Cards) que planeamos --}}
    <div class="row">
        @forelse($vehicles as $vehicle)
            <div class="col-md-4 mb-4">
                {{-- Contenido de la tarjeta del auto... --}}
            </div>
        @empty
            <div class="col-12 text-center">
                <p class="text-muted">No hay vehículos disponibles en este momento. 🚩</p>
            </div>
        @endforelse
    </div>
</div>

{{-- Aquí incluiremos el modal de reserva más adelante --}}
@endsection