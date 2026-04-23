<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Http\Requests\StoreUserRequest;

class UserController extends Controller
{
    /**
     * Listado de usuarios con filtro activos/inactivos.
     */
    public function index()
    {
        $token = session('access_token');
        if (! $token) {
            return redirect()->route('login')->with('error', 'Sesión expirada.');
        }

        // Solo administrador puede acceder
        $authUser = session('auth_user');
        if (!$authUser || ($authUser['role_id'] ?? null) != 1) {
            return redirect()->route('dashboard')->with('error', 'No autorizado.');
        }

        $filter = request()->query('filter'); // 'inactive' o null

        try {
            $client = new Client([
                'base_uri' => config('services.academy_api.url'),
                'timeout'  => 10,
            ]);

            // El backend solo tiene /api/users (no hay /api/users/inactivo separado)
            $response = $client->get('/api/users', [
                'headers' => [
                    'Accept'        => 'application/json',
                    'Authorization' => 'Bearer ' . $token,
                ],
            ]);

            $body = json_decode($response->getBody(), true);
            // Estructura paginada: data.data
            $usersData = $body['data'] ?? [];
            $allUsers = $usersData['data'] ?? $usersData;

            // Filtrar manualmente según el parámetro ?filter=inactive
            // ya que el backend nos devuelve todos los registros juntos.
            if ($filter === 'inactive') {
                // Solo usuarios con deleted_at no vacío
                $users = array_filter($allUsers, function ($user) {
                    return !empty($user['deleted_at']);
                });
            } else {
                // Por defecto, solo usuarios activos (deleted_at == null)
                $users = array_filter($allUsers, function ($user) {
                    return empty($user['deleted_at']);
                });
            }

            return view('layouts.users.index', compact('users', 'filter'));
        } catch (RequestException $e) {
            return redirect()->route('dashboard.admin')->with('error', 'Error al cargar usuarios.');
        }
    }

    /**
     * Formulario de creación.
     */
    public function create()
    {
        $token = session('access_token');
        if (! $token) return redirect()->route('login');

        $authUser = session('auth_user');
        if (!$authUser || ($authUser['role_id'] ?? null) != 1) {
            return redirect()->route('dashboard')->with('error', 'No autorizado.');
        }

        // Roles en español para el select
        $roles = [
            ['id' => 1, 'rol_name' => 'Administrador'],
            ['id' => 2, 'rol_name' => 'Operador'],
            ['id' => 3, 'rol_name' => 'Chofer'],
        ];

        return view('layouts.users.register', compact('roles'));
    }

    /**
     * Guardar nuevo usuario.
     */
    public function store(StoreUserRequest $request)
    {
        $token = session('access_token');
        if (! $token) return redirect()->route('login');

        $validated = $request->validated();

        try {
            $client = new Client(['base_uri' => config('services.academy_api.url')]);
            $client->post('/api/users', [
                'headers' => [
                    'Accept'        => 'application/json',
                    'Authorization' => 'Bearer ' . $token,
                ],
                'json' => $validated,
            ]);

            return redirect()->route('users.index')->with('success', 'Usuario creado.');
        } catch (RequestException $e) {
            $response = $e->getResponse();
            if ($response) {
                $body = json_decode($response->getBody(), true);
                $errors = $body['errors'] ?? [];
                return back()->withErrors($errors ?: ['api' => $body['message'] ?? 'Error'])->withInput();
            }
            return back()->withErrors(['api' => 'No se pudo conectar con el backend.'])->withInput();
        }
    }

    /**
     * Formulario de edición.
     */
    public function edit($id)
    {
        $token = session('access_token');
        if (! $token) return redirect()->route('login');

        try {
            $client = new Client(['base_uri' => config('services.academy_api.url')]);

            $userResponse = $client->get("/api/users/{$id}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept'        => 'application/json',
                ],
            ]);

            $body = json_decode($userResponse->getBody(), true);
            // El backend devuelve el usuario directamente (no envuelto en 'data')
            $user = $body;

            $roles = [
                ['id' => 1, 'rol_name' => 'Administrador'],
                ['id' => 2, 'rol_name' => 'Operador'],
                ['id' => 3, 'rol_name' => 'Chofer'],
            ];

            return view('layouts.users.edit', compact('user', 'roles'));
        } catch (RequestException $e) {
            return redirect()->route('users.index')->with('error', 'Error al cargar usuario.');
        }
    }

    /**
     * Actualizar usuario.
     */
    public function update(StoreUserRequest $request, $id)
    {
        $token = session('access_token');
        if (! $token) return redirect()->route('login');

        try {
            $client = new Client(['base_uri' => config('services.academy_api.url')]);
            $client->put("/api/users/{$id}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept'        => 'application/json',
                ],
                'json' => $request->validated(),
            ]);

            return redirect()->route('users.index')->with('success', 'Usuario actualizado.');
        } catch (RequestException $e) {
            $response = $e->getResponse();
            if ($response) {
                $body = json_decode($response->getBody(), true);
                $errors = $body['errors'] ?? [];
                return back()->withErrors($errors ?: ['api' => $body['message'] ?? 'Error'])->withInput();
            }
            return back()->withErrors(['api' => 'Error de conexión.'])->withInput();
        }
    }

    /**
     * Soft delete (inactivar).
     */
    public function destroy($id)
    {
        $token = session('access_token');
        if (! $token) return redirect()->route('login');

        try {
            $client = new Client(['base_uri' => config('services.academy_api.url')]);
            $client->delete("/api/users/{$id}", [
                'headers' => [
                    'Accept'        => 'application/json',
                    'Authorization' => 'Bearer ' . $token,
                ],
            ]);

            return redirect()->route('users.index')->with('success', 'Usuario inactivado.');
        } catch (RequestException $e) {
            return redirect()->route('users.index')->with('error', 'Error al inactivar.');
        }
    }

    /**
     * Reactivar usuario.
     */
    public function restore($id)
    {
        $token = session('access_token');
        if (! $token) return redirect()->route('login');

        try {
            $client = new Client(['base_uri' => config('services.academy_api.url')]);
            // El backend usa Route Model Binding, así que solo PATCH a /api/users/{id}/restore
            $client->patch("/api/users/{$id}/restore", [
                'headers' => [
                    'Accept'        => 'application/json',
                    'Authorization' => 'Bearer ' . $token,
                ],
            ]);

            return redirect()->route('users.index', ['filter' => 'inactive'])->with('success', 'Usuario reactivado.');
        } catch (RequestException $e) {
            return redirect()->route('users.index', ['filter' => 'inactive'])->with('error', 'Error al reactivar.');
        }
    }
}