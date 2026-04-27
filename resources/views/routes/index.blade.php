@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Rutas Disponibles 🗺️</h2>
    
    <div class="row">
        @foreach($routes as $route)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ $route['name'] }}</h5>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-0 text-muted small">ORIGEN</p>
                                <span class="fw-bold">🚩 {{ $route['origin'] }}</span>
                            </div>
                            <div class="text-secondary">➡️</div>
                            <div class="text-end">
                                <p class="mb-0 text-muted small">DESTINO</p>
                                <span class="fw-bold">{{ $route['destination'] }} 🏁</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0 d-flex justify-content-between">
                        <span class="badge bg-info text-dark">{{ $route['distance'] }} KM</span>
                        <a href="{{ route('routes.edit', $route['id']) }}" class="btn btn-sm btn-outline-warning">Editar</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection