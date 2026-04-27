<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\StoreRouteRequest;

class RouteController extends Controller
{
    public function index(Request $request)
{
    $token = session('access_token');
    if (! $token) {
        return redirect()->route('login')->with('error', 'Sesión expirada.');
    }

    // Solo Operador (rol 2) puede ver rutas
    $user = session('auth_user');
    $role = $user['role_id'] ?? null;
    if ($role != 2) {
        return redirect()->route('dashboard')->with('error', 'No autorizado.');
    }

    $filter = $request->get('filter', '');

    try {
        
        if ($filter === 'inactive') {
            $endpoint = '/api/routes/inactive';  // ← Endpoint especial para inactivas
        } else {
            $endpoint = '/api/routes';           // ← Endpoint normal (solo activas)
        }

        $response = Http::withToken($token)
            ->timeout(5)
            ->get(config('services.academy_api.url') . $endpoint, [
                'page' => $request->get('page', 1)
            ]);

        if ($response->successful()) {
            $data = $response->json();
            $routes = $data['data']['data'] ?? $data['data'] ?? [];
            $pagination = [
                'current_page'  => $data['data']['current_page'] ?? $data['current_page'] ?? 1,
                'last_page'     => $data['data']['last_page'] ?? $data['last_page'] ?? 1,
                'next_page_url' => $data['data']['next_page_url'] ?? $data['next_page_url'] ?? null,
                'prev_page_url' => $data['data']['prev_page_url'] ?? $data['prev_page_url'] ?? null,
            ];
            return view('layouts.routes.index', compact('routes', 'filter', 'pagination'));
        }

        if ($response->status() === 403) {
            return view('layouts.routes.index', [
                'routes'     => [],
                'filter'     => $filter,
                'pagination' => null,
            ])->with('error', 'No tienes permiso para acceder a las rutas según la política del servidor.');
        }

        return back()->with('error', 'Error de la API: ' . ($response->json()['message'] ?? 'Desconocido'));

    } catch (\Exception $e) {
        return view('layouts.routes.index', [
            'routes'     => [],
            'filter'     => $filter,
            'pagination' => null,
        ])->with('error', 'No se pudo establecer conexión con el servidor.');
    }
}

    public function create()
    {
        $token = session('access_token');
        if (!$token) return redirect()->route('login')->with('error', 'Sesión expirada.');

        $user = session('auth_user');
        $role = $user['role_id'] ?? null;
        if ($role != 2) {
            return redirect()->route('dashboard')->with('error', 'No autorizado.');
        }

        return view('layouts.routes.register');
    }

    public function store(StoreRouteRequest $request)
    {
        $token = session('access_token');
        if (!$token) return redirect()->route('login');

        $user = session('auth_user');
        $role = $user['role_id'] ?? null;
        if ($role != 2) {
            return redirect()->route('dashboard')->with('error', 'No autorizado.');
        }

        try {
            $response = Http::withToken($token)
                ->post(config('services.academy_api.url') . '/api/routes', $request->validated());

            if ($response->successful()) {
                return redirect()->route('routes.index')->with('success', 'Ruta creada correctamente.');
            }

            $body = $response->json();
            $errors = $body['errors'] ?? [];
            $message = $body['message'] ?? 'Error al guardar la ruta.';
            return back()->withErrors($errors ?: ['api' => $message])->withInput();

        } catch (\Exception $e) {
            return back()->withErrors(['api' => 'Error de conexión: ' . $e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        $token = session('access_token');
        if (!$token) return redirect()->route('login');

        $user = session('auth_user');
        $role = $user['role_id'] ?? null;
        if ($role != 2) {
            return redirect()->route('dashboard')->with('error', 'No autorizado.');
        }

        try {
            $response = Http::withToken($token)
                ->get(config('services.academy_api.url') . "/api/routes/{$id}");

            if ($response->successful()) {
                $route = $response->json();
                return view('layouts.routes.edit', compact('route'));
            }

            return redirect()->route('routes.index')->with('error', 'Ruta no encontrada.');

        } catch (\Exception $e) {
            return redirect()->route('routes.index')->with('error', 'Error de conexión.');
        }
    }

    public function update(StoreRouteRequest $request, $id)
    {
        $token = session('access_token');
        if (!$token) return redirect()->route('login');

        $user = session('auth_user');
        $role = $user['role_id'] ?? null;
        if ($role != 2) {
            return redirect()->route('dashboard')->with('error', 'No autorizado.');
        }

        try {
            $response = Http::withToken($token)
                ->put(config('services.academy_api.url') . "/api/routes/{$id}", $request->validated());

            if ($response->successful()) {
                return redirect()->route('routes.index')->with('success', 'Ruta actualizada correctamente.');
            }

            $body = $response->json();
            $errors = $body['errors'] ?? [];
            $message = $body['message'] ?? 'Error al actualizar la ruta.';
            return back()->withErrors($errors ?: ['api' => $message])->withInput();

        } catch (\Exception $e) {
            return back()->withErrors(['api' => 'Error de conexión: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        $token = session('access_token');
        if (!$token) return redirect()->route('login');

        $user = session('auth_user');
        $role = $user['role_id'] ?? null;
        if ($role != 2) {
            return redirect()->route('dashboard')->with('error', 'No autorizado.');
        }

        try {
            $response = Http::withToken($token)
                ->delete(config('services.academy_api.url') . "/api/routes/{$id}");

            if ($response->successful()) {
                return redirect()->route('routes.index')->with('success', 'Ruta inactivada correctamente.');
            }

            return back()->with('error', 'No se pudo inactivar la ruta.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error de comunicación con el servidor.');
        }
    }

    public function restore($id)
    {
        $token = session('access_token');
        if (!$token) return redirect()->route('login');

        $user = session('auth_user');
        $role = $user['role_id'] ?? null;
        if ($role != 2) {
            return redirect()->route('dashboard')->with('error', 'No autorizado.');
        }

        try {
            $response = Http::withToken($token)
                ->patch(config('services.academy_api.url') . "/api/routes/{$id}/restore");

            if ($response->successful()) {
                return redirect()->route('routes.index', ['filter' => 'inactive'])
                    ->with('success', 'Ruta reactivada correctamente.');
            }

            return back()->with('error', 'No se pudo reactivar la ruta.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error de comunicación con el servidor.');
        }
    }
}