<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\Request;               // ← NUEVO (para type‑hint Request)
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    /**
     * Listado de usuarios con filtro activos/inactivos.
     */

    public function index(Request $request)
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

    $filter = $request->query('filter'); // 'inactive' o null

    try {
        
        if ($filter === 'inactive') {
            $endpoint = '/api/users/inactive';  // ← Método inactive() del backend
        } else {
            $endpoint = '/api/users';           // ← Método index() normal
        }

        $response = Http::withToken($token)
            ->timeout(10)
            ->get(config('services.academy_api.url') . $endpoint, [
                'page' => $request->get('page', 1)
            ]);

        if ($response->successful()) {
            $data = $response->json();
            // El backend devuelve estructura paginada: { data: { data: [...], current_page, ... } }
            $users = $data['data']['data'] ?? $data['data'] ?? [];
            return view('layouts.users.index', compact('users', 'filter'));
        }

        return back()->with('error', 'Error de la API: ' . ($response->json()['message'] ?? 'Desconocido'));

    } catch (\Exception $e) {
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