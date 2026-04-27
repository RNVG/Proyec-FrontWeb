<?php
namespace App\Http\Controllers;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;    
use Illuminate\Http\Request;

class RequestController extends Controller
{

    public function create()
    {
        $token = session('access_token');

        try {
            // Hacemos la petición a la API filtrando por 'available'
            $response = Http::withToken($token)
                ->get(config('services.academy_api.url') . "/api/vehicle", [
                    'status_filter' => 'available' 
                ]);

            if ($response->successful()) {
                $vehicles = $response->json()['data']['data'] ?? [];
                return view('requests.catalog', compact('vehicles'));
            }
            
            return back()->with('error', 'No se pudo cargar el catálogo de vehículos.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error de conexión con el servidor.');
        }
    }

    public function store(Request $request)
    {
    // 1. Validamos en el Frontend antes de llamar a la API (Requisito E.3)
        $request->validate([
            'vehicle_id' => 'required',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after:start_date', // El fin debe ser después del inicio
        ], [
            'end_date.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
        ]);

        $token = session('access_token');

        try {
            $response = Http::withToken($token)->post(config('services.academy_api.url') . "/api/requests", [
                'vehicle_id'   => $request->vehicle_id,
                'start_date'   => $request->start_date,
                'end_date'     => $request->end_date,
                'observations' => $request->observations,
                'status'       => 'pending', // Por defecto según E.2.2
            ]);

            if ($response->successful()) {
                return redirect()->route('requests.index')->with('success', 'Solicitud creada con éxito.');
            }

            return back()->withErrors(['api' => 'Error al crear la solicitud.']);
        } catch (\Exception $e) {
            return back()->withErrors(['api' => 'Conexión fallida con el servidor.']);
        }
    }



    public function catalog()
{
    $token = session('access_token');

    try {
        $response = Http::withToken($token)
            ->get(config('services.academy_api.url') . "/api/vehicle", [
                'status_filter' => 'available' 
            ]);

        if ($response->successful()) {
            $vehicles = $response->json()['data']['data'] ?? [];
            return view('requests.catalog', compact('vehicles'));
        }
        
        return back()->with('error', 'No se pudo cargar el catálogo.');
    } catch (\Exception $e) {
        return back()->with('error', 'Error de conexión.');
    }
}
    
}
