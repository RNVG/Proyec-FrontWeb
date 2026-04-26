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
    
    // Capturamos el filtro de la URL, por defecto es 'active'
        $status = $request->get('status_filter', 'active');

        $response = Http::withToken($token)
            ->get(config('services.academy_api.url') . "/api/vehicle", [
                'status_filter' => $status // Enviamos el parámetro a la API
            ]);

        if ($response->successful()) {
            // Como ellos usan paginate(10), los datos están en data -> data
            $vehicles = $response->json()['data']['data'];
            return view('vehicles.index', compact('vehicles', 'status'));
        }

        return back()->with('error', 'No se pudieron cargar los datos.');

    }

    public function show(){
        
    }


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
       // 1. Validamos los datos básicos
    $request->validate([
        'plate' => 'required',
        'image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Máximo 2MB
    ]);

    $imageUrl = null;

    // 2. Lógica de la Imagen: Guardar localmente en el Front
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        
        // Creamos un nombre único: ej. 1714123456_placa.jpg
        $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        
        // Movemos el archivo a la carpeta pública
        // Importante: Asegúrate de que exista la carpeta public/uploads/vehicles/
        $file->move(public_path('uploads/vehicles'), $fileName);
        
        // Generamos la URL completa que guardaremos como string
        $imageUrl = asset('uploads/vehicles/' . $fileName);
    }

    try {
        // 3. Obtener el token de la sesión
        $token = session('access_token');

        // 4. Enviar los datos a la API (incluyendo la URL de la imagen como texto)
        $response = Http::withToken($token)->post(config('services.academy_api.url') . '/api/vehicle', [
            'plate'      => $request->plate,
            'brand'      => $request->brand,
            'model'      => $request->model,
            'year'       => $request->year,
            'type'       => $request->type,
            'capacity'   => $request->capacity,
            'fuel_type'  => $request->fuel_type,
            'status'     => $request->status,
            'image'      => $imageUrl, // Mandamos el STRING de la URL
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
        // Obtener el vehículo desde el backend
        $token = session('access_token');
        if (!$token) {
            return redirect()->route('login')->with('error', 'La sesión ha expirado. Inicia sesión nuevamente.');
        }

        try {
            // Pedimos a la API los datos del vehículo específico
            $response = Http::withToken($token)
                ->get(config('services.academy_api.url') . "/api/vehicle/{$id}");

            if ($response->successful()) {
                $vehicle = $response->json()['data']; 
                return view('vehicles.edit', compact('vehicle'));
            }

            return redirect()->route('vehicle.index')->with('error', 'Vehículo no encontrado.');
        } catch (\Exception $e) {
            return redirect()->route('vehicle.index')->with('error', 'Error de conexión.');
        }
    }

    public function update(Request $request, $id)
    {
    $imageUrl = $request->old_image;

    // Si el usuario subió una NUEVA imagen
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        $file->move(public_path('uploads/vehicles'), $fileName);
        
        // Actualizamos el string de la URL
        $imageUrl = asset('uploads/vehicles/' . $fileName);
    }

    try {
        $token = session('access_token');
        // Enviamos el PUT a la API con todos los campos (incluyendo la URL de la imagen)
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
                'image'      => $imageUrl, // Mandamos la nueva URL o la anterior
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
        $token = session('access_token');
        try {
            // Importante usar PATCH y la ruta correcta que definieron en el Back
            $response = Http::withToken($token)
                ->patch(config('services.academy_api.url') . "/api/vehicle/{$id}/restore");

            if ($response->successful()) {
                return redirect()->route('vehicle.index')->with('success', 'Vehículo activado nuevamente.');
            }

            return back()->with('error', 'No se pudo restaurar el vehículo.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error de comunicación.');
        }
    }




    


}
