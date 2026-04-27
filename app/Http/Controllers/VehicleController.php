<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Exception;

class VehicleController extends Controller
{

    private function getClient()
    {
        return new Client([
            'base_uri' => config('services.academy_api.url'),
            'timeout'  => 10,
        ]);
    }

    private function getHeaders()
    {
        return [
            'Accept'        => 'application/json',
            'Authorization' => 'Bearer ' . session('access_token'),
        ];
    }
    private function checkSession()
    {
        if (! session('access_token')) {
            return redirect()
                ->route('login')
                ->with('error', 'La sesión ha expirado.');
        }
        return null;
    }
    public function index(Request $request)
    {
        $token = session('access_token');
        $role = session('user_role');


        $status = ($role == 3) ? 'available' : $request->get('status_filter', 'all');

        try {
            $response = Http::withToken($token)
                ->timeout(5) // Evita que la página se quede cargando si la API cae
                ->get(config('services.academy_api.url') . "/api/vehicle", [
                    'status_filter' => $status,
                    'page' => $request->get('page', 1) // Enviamos la página actual para que la paginación funcione
                ]);
            if ($response->successful()) {
                $data = $response->json();

                
                $vehicles = $data['data']['data'] ?? [];

                // 3. Paginación manual (opcional)
                // Si quieres usar los links de abajo (anterior/siguiente), pasamos la info de paginación
                $pagination = [
                    'current_page' => $data['data']['current_page'] ?? 1,
                    'last_page' => $data['data']['last_page'] ?? 1,
                    'next_page_url' => $data['data']['next_page_url'] ?? null,
                    'prev_page_url' => $data['data']['prev_page_url'] ?? null,
                ];

                return view('vehicles.index', compact('vehicles', 'status', 'role', 'pagination'));
            }

            return back()->with('error', 'Error de la API: ' . ($response->json()['message'] ?? 'Desconocido'));
        } catch (\Exception $e) {
            // En caso de que el servidor de la API esté apagado (muy común en desarrollo local)
            return view('vehicles.index', [
                'vehicles' => [],
                'status' => $status,
                'role' => $role,
                'pagination' => null
            ])->with('error', 'No se pudo establecer conexión con el servidor de datos.');
        }
    }
    public function show() {}

    public function create()
    {
        // Vista de formulario de creación
        $token = session('access_token');
        if (!$token) {
            return redirect()
                ->route('login')
                ->with('error', 'La sesión ha expirado. Inicia sesión nuevamente.');
        }
        return view('vehicles.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'plate' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Máximo 2MB
        ]);

        $imageUrl = null;


        if ($request->hasFile('image')) {
            $file = $request->file('image');

            $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());

            $file->move(public_path('uploads/vehicles'), $fileName);

            $imageUrl = asset('uploads/vehicles/' . $fileName);
        }

        try {
            $token = session('access_token');

            $response = Http::withToken($token)->post(config('services.academy_api.url') . '/api/vehicle', [
                'plate'      => $request->plate,
                'brand'      => $request->brand,
                'model'      => $request->model,
                'year'       => $request->year,
                'type'       => $request->type,
                'capacity'   => $request->capacity,
                'fuel_type'  => $request->fuel_type,
                'status'     => $request->status,
                'image'      => $imageUrl, 
            ]);

            if ($response->successful()) {
                return redirect()->route('vehicle.index')
                    ->with('success', 'Vehículo registrado y foto guardada localmente.');
            }

            return back()->withErrors('Error al guardar en la API: ' . $response->body())->withInput();
        } catch (\Exception $e) {
            return back()->withErrors('Error de conexión: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $token = session('access_token');
        if (!$token) {
            return redirect()->route('login')->with('error', 'La sesión ha expirado. Inicia sesión nuevamente.');
        }        try {
            $response = Http::withToken($token)
                ->get(config('services.academy_api.url') . "/api/vehicle/{$id}");
            if ($response->successful()) {
                $vehicle = $response->json()['data'];
                return view('vehicles.edit', compact('vehicle'));
s            }
            return redirect()->route('vehicle.index')->with('error', 'Vehículo no encontrado.');
        } catch (\Exception $e) {
            return redirect()->route('vehicle.index')->with('error', 'Error de conexión.');
        }
    }
    public function update(Request $request, $id)
    {
        $imageUrl = $request->old_image;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads/vehicles'), $fileName);

            $imageUrl = asset('uploads/vehicles/' . $fileName);
        }
        try {
            $token = session('access_token');
            $response = Http::withToken($token)
                ->put(config('services.academy_api.url') . "/api/vehicle/{$id}", [
                    'plate'      => $request->plate,
                    'brand'      => $request->brand,
                    'model'      => $request->model,
                    'year'       => $request->year,
                    'type'       => $request->type,
                    'capacity'   => $request->capacity,
                    'fuel_type'  => $request->fuel_type,
                    'status'     => $request->status,
                    'image'      => $imageUrl, 
                ]);
            if ($response->successful()) {
                return redirect()->route('vehicle.index')->with('success', 'Vehículo actualizado correctamente.');
            }

            return back()->withErrors('Error al actualizar en la API.')->withInput();
        } catch (\Exception $e) {
            return back()->withErrors('Error de conexión con el servidor.')->withInput();
        }
    }

    public function destroy($id)
    {
        $token = session('access_token');
        try {
            $response = Http::withToken($token)
                ->delete(config('services.academy_api.url') . "/api/vehicle/{$id}");

            if ($response->successful()) {
                return redirect()->route('vehicle.index')->with('success', 'Vehículo desactivado correctamente.');
            }

            return back()->with('error', 'No se pudo desactivar el vehículo.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error de comunicación con el servidor.');
        }
    }

    public function restore($id)
    {
        if ($redirect = $this->checkSession()) return $redirect;
        try {
            $this->getClient()->patch("/api/vehicle/{$id}/restore", [
                'headers' => $this->getHeaders(),
            ]);
            return redirect()->route('vehicle.index')->with('success', 'Vehículo reactivado correctamente.');
        } catch (RequestException $e) {
            Log::error("Error al reactivar vehículo ID {$id}: " . $e->getMessage());
            return back()->with('error', 'No se pudo reactivar el vehículo.');
        }
    }
}
