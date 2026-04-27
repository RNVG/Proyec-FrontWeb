<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MaintenanceController;

/*
|--------------------------------------------------------------------------
| Rutas Públicas
|--------------------------------------------------------------------------
*/

// Redirección raíz al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Autenticación
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Rutas Protegidas (requieren sesión activa)
|--------------------------------------------------------------------------
*/

// Dashboard general: redirige según el rol del usuario autenticado
Route::get('/dashboard', function () {
    $user = session('auth_user');
    if (! $user) {
        return redirect()->route('login');
    }
    return match ($user['role_id'] ?? null) {
        1 => redirect()->route('dashboard.admin'),
        2 => redirect()->route('dashboard.operator'),
        3 => redirect()->route('dashboard.driver'),
        default => redirect()->route('login'),
    };
})->name('dashboard');

// Dashboard del Administrador (role_id = 1)
Route::get('/dashboard/admin', function () {
    $user = session('auth_user');
    if (! $user || ($user['role_id'] ?? null) != 1) {
        return redirect()->route('login');
    }
    return view('layouts.partials.dashboard-content');
})->name('dashboard.admin');

// Dashboard del Operador (role_id = 2)
Route::get('/dashboard/operator', function () {
    $user = session('auth_user');
    if (! $user || ($user['role_id'] ?? null) != 2) {
        return redirect()->route('login');
    }
    return view('layouts.partials.dashboard-content-operator');
})->name('dashboard.operator');

// Dashboard del Chofer (role_id = 3)
Route::get('/dashboard/driver', function () {
    $user = session('auth_user');
    if (! $user || ($user['role_id'] ?? null) != 3) {
        return redirect()->route('login');
    }
    return view('layouts.partials.dashboard-content-driver');
})->name('dashboard.driver');

/*
|--------------------------------------------------------------------------
| Módulo de Usuarios (solo Administrador)
|--------------------------------------------------------------------------
*/
Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');               // Listado
    Route::get('/register', [UserController::class, 'create'])->name('register');   // Formulario creación
    Route::post('/', [UserController::class, 'store'])->name('store');              // Guardar nuevo
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');      // Formulario edición
    Route::put('/{user}', [UserController::class, 'update'])->name('update');       // Actualizar
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');  // Soft delete
    Route::patch('/{user}/restore', [UserController::class, 'restore'])->name('restore'); // Reactivar
});

Route::prefix('vehicle')->name('vehicle.')->group(function () {
    Route::get('/', [VehicleController::class, 'index'])->name('index');
    Route::get('/create', [VehicleController::class, 'create'])->name('create');
    Route::get('/{id}/edit', [VehicleController::class, 'edit'])->name('edit');
    Route::get('/{id}', [VehicleController::class, 'show'])->name('show');
    Route::post('/', [VehicleController::class, 'store'])->name('store');
    Route::patch('/{id}/restore', [VehicleController::class, 'restore'])->name('restore');
    Route::put('/{id}', [VehicleController::class, 'update'])->name('update');
    Route::delete('/{id}', [VehicleController::class, 'destroy'])->name('destroy');
});

Route::get('/catalog', [RequestController::class, 'catalog'])->name('requests.catalog');


// Rutas para gestionar las rutas
Route::prefix('routes')->name('routes.')->group(function () {
    Route::get('/', [RouteController::class, 'index'])->name('index');
    Route::get('/create', [RouteController::class, 'create'])->name('create');
    Route::post('/', [RouteController::class, 'store'])->name('store');
    Route::get('/{route}/edit', [RouteController::class, 'edit'])->name('edit');
    Route::put('/{route}', [RouteController::class, 'update'])->name('update');
    Route::delete('/{route}', [RouteController::class, 'destroy'])->name('destroy');
    Route::patch('/{route}/restore', [RouteController::class, 'restore'])->name('restore');
});

// Rutas para gestionar los viajes realizados

Route::prefix('trips')->name('trips.')->group(function () {
    Route::get('/', [TripController::class, 'index'])->name('index');
    Route::get('/create', [TripController::class, 'create'])->name('create');
    Route::get('/{id}/edit', [TripController::class, 'edit'])->name('edit');
    Route::get('/{id}', [TripController::class, 'show'])->name('show');
    Route::post('/', [TripController::class, 'store'])->name('store');
    Route::patch('/{id}/restore', [TripController::class, 'restore'])->name('restore');
    Route::put('/{id}', [TripController::class, 'update'])->name('update');
    Route::delete('/{id}', [TripController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| Módulo de Reportes (solo Administrador)
|--------------------------------------------------------------------------
*/
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('index'); // ← NUEVO
    Route::get('/available-vehicles', [ReportController::class, 'availableVehicles'])->name('available');
    Route::get('/fleet-usage', [ReportController::class, 'fleetUsage'])->name('fleetUsage');
    Route::get('/driver-history', [ReportController::class, 'driverHistory'])->name('driverHistory');
});

/*
|--------------------------------------------------------------------------
| Módulo de Mantenimientos (Admin y Operador)
|--------------------------------------------------------------------------
*/
Route::prefix('maintenances')->name('maintenances.')->group(function () {
    Route::get('/', [MaintenanceController::class, 'index'])->name('index');
    Route::get('/create', [MaintenanceController::class, 'create'])->name('create');
    Route::post('/', [MaintenanceController::class, 'store'])->name('store');
    Route::get('/{maintenance}/edit', [MaintenanceController::class, 'edit'])->name('edit');
    Route::put('/{maintenance}', [MaintenanceController::class, 'update'])->name('update');
    Route::delete('/{maintenance}', [MaintenanceController::class, 'destroy'])->name('destroy');
    Route::patch('/{maintenance}/restore', [MaintenanceController::class, 'restore'])->name('restore');
    Route::patch('/{maintenance}/complete', [MaintenanceController::class, 'complete'])->name('complete');
});

/*
Route::get('/users/register', function () {
    return view('layouts.users.register');
})->name('users.register');

// Listar usuarios (activos/inactivos)
Route::get('/users', [UserController::class, 'index'])->name('users.index');

// Crear usuario
Route::post('/users', [UserController::class, 'store'])->name('use rs.store');

// Editar (formulario)
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');

// Actualizar
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');

// Inactivar
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

// Reactivar
Route::patch('/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
*/
