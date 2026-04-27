<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class RouteController extends Controller
{
    public function index()
    {
        $token = session('access_token');

        $response = Http::withToken($token)
            ->get(config('services.academy_api.url') . '/api/routes');

        if ($response->successful()) {
            $routes = $response->json()['data'] ?? [];
            return view('routes.index', compact('routes'));
        }

        return back()->with('error', 'Error al conectar con la API de rutas.');
    }
    public function create()
    {
        return view('routes.register');
    }


public function store(Request $request)
{
    // Validamos los datos antes de enviarlos a la API 🛡️
    $request->validate([
        'name'        => 'required|string|max:255',
        'origin'      => 'required|string',
        'destination' => 'required|string',
        'distance'    => 'required|numeric',
    ]);

    $response = Http::withToken(session('access_token'))
        ->post(config('services.academy_api.url') . '/api/routes', $request->all());

    if ($response->successful()) {
        return redirect()->route('routes.index')->with('success', '¡Ruta creada con éxito! 🏁');
    }

    return back()->with('error', 'No se pudo crear la ruta en la API.');
}
}
