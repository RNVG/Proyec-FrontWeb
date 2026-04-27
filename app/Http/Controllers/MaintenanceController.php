<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMaintenanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MaintenanceController extends Controller
{
    /**
     * Listado de mantenimientos con filtro activos/inactivos.
     */
    public function index(Request $request)
    {
        $token = session('access_token');
        if (!$token) {
            return redirect()->route('login')->with('error', 'Sesión expirada.');
        }

        // Solo Admin (1) y Operador (2) pueden acceder
        $user = session('auth_user');
        $role = $user['role_id'] ?? null;
        if (!in_array($role, [1, 2])) {
            return redirect()->route('dashboard')->with('error', 'No autorizado.');
        }

        $filter = $request->get('filter', '');

        try {
            // El backend utiliza apiResource para 'maintenance' → ruta base /api/maintenance
            $endpoint = '/api/maintenance';
            $query = ['page' => $request->get('page', 1)];
            if ($filter === 'inactive') {
                $query['inactive'] = 'true';
            }

            $response = Http::withToken($token)
                ->timeout(10)
                ->get(config('services.academy_api.url') . $endpoint, $query);

            if ($response->successful()) {
                $data = $response->json();
                $maintenances = $data['data']['data'] ?? [];
                $pagination = [
                    'current_page'  => $data['data']['current_page'] ?? 1,
                    'last_page'     => $data['data']['last_page'] ?? 1,
                    'next_page_url' => $data['data']['next_page_url'] ?? null,
                    'prev_page_url' => $data['data']['prev_page_url'] ?? null,
                ];
                return view('layouts.maintenances.index', compact('maintenances', 'filter', 'pagination'));
            }

            return back()->with('error', 'Error de la API: ' . ($response->json()['message'] ?? 'Desconocido'));

        } catch (\Exception $e) {
            return view('layouts.maintenances.index', [
                'maintenances' => [],
                'filter'       => $filter,
                'pagination'   => null,
            ])->with('error', 'No se pudo establecer conexión con el servidor.');
        }
    }

    /**
     * Formulario de creación.
     */
    public function create()
    {
        $token = session('access_token');
        if (!$token) {
            return redirect()->route('login')->with('error', 'Sesión expirada.');
        }

        $user = session('auth_user');
        $role = $user['role_id'] ?? null;
        if (!in_array($role, [1, 2])) {
            return redirect()->route('dashboard')->with('error', 'No autorizado.');
        }

        // Obtener vehículos disponibles desde la API
        try {
            $response = Http::withToken($token)
                ->timeout(10)
                ->get(config('services.academy_api.url') . '/api/vehicle?status_filter=available&per_page=100');
            $vehicles = $response->successful() ? ($response->json()['data']['data'] ?? []) : [];
        } catch (\Exception $e) {
            $vehicles = [];
        }

        return view('layouts.maintenances.register', compact('vehicles'));
    }

    /**
     * Guardar nuevo mantenimiento.
     */
    public function store(StoreMaintenanceRequest $request)
    {
        $token = session('access_token');
        if (!$token) {
            return redirect()->route('login');
        }

        try {
            $response = Http::withToken($token)
                ->post(config('services.academy_api.url') . '/api/maintenance', $request->validated());

            if ($response->successful()) {
                return redirect()->route('maintenances.index')->with('success', 'Mantenimiento registrado.');
            }

            $body = $response->json();
            $errors = $body['errors'] ?? [];
            $message = $body['message'] ?? 'Error al guardar.';
            return back()->withErrors($errors ?: ['api' => $message])->withInput();

        } catch (\Exception $e) {
            return back()->withErrors(['api' => 'Error de conexión: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Formulario de edición.
     */
    public function edit($id)
    {
        $token = session('access_token');
        if (!$token) {
            return redirect()->route('login');
        }

        try {
            $response = Http::withToken($token)
                ->get(config('services.academy_api.url') . "/api/maintenance/{$id}");

            if ($response->successful()) {
                $maintenance = $response->json()['data'] ?? $response->json();
                // Obtener vehículos para el select
                $vehResponse = Http::withToken($token)
                    ->get(config('services.academy_api.url') . '/api/vehicle?per_page=100');
                $vehicles = $vehResponse->successful() ? ($vehResponse->json()['data']['data'] ?? []) : [];

                return view('layouts.maintenances.edit', compact('maintenance', 'vehicles'));
            }

            return redirect()->route('maintenances.index')->with('error', 'Mantenimiento no encontrado.');

        } catch (\Exception $e) {
            return redirect()->route('maintenances.index')->with('error', 'Error de conexión.');
        }
    }

    /**
     * Actualizar mantenimiento.
     */
    public function update(StoreMaintenanceRequest $request, $id)
    {
        $token = session('access_token');
        if (!$token) {
            return redirect()->route('login');
        }

        try {
            $response = Http::withToken($token)
                ->put(config('services.academy_api.url') . "/api/maintenance/{$id}", $request->validated());

            if ($response->successful()) {
                return redirect()->route('maintenances.index')->with('success', 'Mantenimiento actualizado.');
            }

            $body = $response->json();
            $errors = $body['errors'] ?? [];
            $message = $body['message'] ?? 'Error al actualizar.';
            return back()->withErrors($errors ?: ['api' => $message])->withInput();

        } catch (\Exception $e) {
            return back()->withErrors(['api' => 'Error de conexión: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Soft delete (cancelar/inactivar).
     */
    public function destroy($id)
    {
        $token = session('access_token');
        if (!$token) {
            return redirect()->route('login');
        }

        try {
            $response = Http::withToken($token)
                ->delete(config('services.academy_api.url') . "/api/maintenance/{$id}");

            if ($response->successful()) {
                return redirect()->route('maintenances.index')->with('success', 'Mantenimiento cancelado.');
            }

            return back()->with('error', 'No se pudo cancelar el mantenimiento.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error de comunicación con el servidor.');
        }
    }

    /**
     * Reactivar mantenimiento.
     */
    public function restore($id)
    {
        $token = session('access_token');
        if (!$token) {
            return redirect()->route('login');
        }

        try {
            $response = Http::withToken($token)
                ->patch(config('services.academy_api.url') . "/api/maintenance/{$id}/restore");

            if ($response->successful()) {
                return redirect()->route('maintenances.index', ['filter' => 'inactive'])
                    ->with('success', 'Mantenimiento reactivado.');
            }

            return back()->with('error', 'No se pudo reactivar el mantenimiento.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error de comunicación con el servidor.');
        }
    }

    /**
     * Completar mantenimiento (liberar vehículo).
     */
    public function complete($id)
    {
        $token = session('access_token');
        if (!$token) {
            return redirect()->route('login');
        }

        try {
            $response = Http::withToken($token)
                ->patch(config('services.academy_api.url') . "/api/maintenances/{$id}/completed");

            if ($response->successful()) {
                return redirect()->route('maintenances.index')->with('success', 'Mantenimiento completado.');
            }

            return back()->with('error', 'No se pudo completar el mantenimiento.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error de comunicación con el servidor.');
        }
    }
}