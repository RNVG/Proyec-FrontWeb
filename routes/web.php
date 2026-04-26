<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;

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

    Route::put('/{id}', [VehicleController::class, 'update'])->name('update');
    Route::delete('/{id}', [VehicleController::class, 'destroy'])->name('destroy');
    Route::patch('/{id}/restore', [VehicleController::class, 'restore'])->name('restore');
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