<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ReportController extends Controller
{

    public function index()
{
    $user = session('auth_user');
    if (!$user || ($user['role_id'] ?? null) != 1) {
        return redirect()->route('dashboard')->with('error', 'No autorizado.');
    }

    return view('layouts.reports.index');
}


    /**
     * Reporte de vehículos disponibles en un rango de fechas.
     */
    public function availableVehicles(Request $request)
    {
        $user = session('auth_user');
        if (!$user || ($user['role_id'] ?? null) != 1) {
            return redirect()->route('dashboard')->with('error', 'No autorizado.');
        }

        $results = null;

        if ($request->has('start_date') && $request->has('end_date')) {
            $request->validate([
                'start_date' => 'required|date',
                'end_date'   => 'required|date|after:start_date',
            ], [
                'end_date.after' => 'La fecha final debe ser mayor a la inicial.'
            ]);

            $token = session('access_token');
            $response = Http::withToken($token)
                ->timeout(10)
                ->get(config('services.academy_api.url') . '/api/reports/available-vehicles', [
                    'start_date' => $request->start_date,
                    'end_date'   => $request->end_date,
                ]);

            if ($response->successful()) {
                $results = $response->json()['data'] ?? [];
            } else {
                return back()->with('error', 'Error al obtener el reporte: ' . ($response->json()['message'] ?? ''));
            }
        }

        return view('layouts.reports.available-vehicles', compact('results'));
    }

    /**
     * Reporte de uso de flotilla en un periodo.
     */
    public function fleetUsage(Request $request)
    {
        $user = session('auth_user');
        if (!$user || ($user['role_id'] ?? null) != 1) {
            return redirect()->route('dashboard')->with('error', 'No autorizado.');
        }

        $results = null;

        if ($request->has('start_date') && $request->has('end_date')) {
            $request->validate([
                'start_date' => 'required|date',
                'end_date'   => 'required|date|after:start_date',
            ], [
                'end_date.after' => 'La fecha final debe ser mayor a la inicial.'
            ]);

            $token = session('access_token');
            $response = Http::withToken($token)
                ->timeout(10)
                ->get(config('services.academy_api.url') . '/api/reports/fleet-usage', [
                    'start_date' => $request->start_date,
                    'end_date'   => $request->end_date,
                ]);

            if ($response->successful()) {
                $results = $response->json()['data'] ?? [];
            } else {
                return back()->with('error', 'Error al obtener el reporte: ' . ($response->json()['message'] ?? ''));
            }
        }

        return view('layouts.reports.fleet-usage', compact('results'));
    }

    /**
     * Historial de un chofer (viajes y solicitudes).
     */
    public function driverHistory(Request $request)
    {
        $user = session('auth_user');
        if (!$user || ($user['role_id'] ?? null) != 1) {
            return redirect()->route('dashboard')->with('error', 'No autorizado.');
        }

        $token = session('access_token');
        // Obtener la lista de choferes (usuarios con rol 3) para el select
        $drivers = [];
        try {
            $response = Http::withToken($token)
                ->timeout(10)
                ->get(config('services.academy_api.url') . '/api/users', ['per_page' => 100]); // ajusta según necesidad
            if ($response->successful()) {
                $users = $response->json()['data']['data'] ?? [];
                // Filtrar choferes (role_id = 3)
                $drivers = array_filter($users, function ($u) {
                    return ($u['role_id'] ?? null) == 3;
                });
            }
        } catch (\Exception $e) {
            // Si falla, el select aparecerá vacío
        }

        $results = null;

        if ($request->has('driver_id')) {
            $request->validate([
                'driver_id' => 'required|integer',
            ]);

            $response = Http::withToken($token)
                ->timeout(10)
                ->get(config('services.academy_api.url') . '/api/reports/driver-history/' . $request->driver_id);

            if ($response->successful()) {
                $results = $response->json()['data'] ?? [];
            } else {
                return back()->with('error', 'Error al obtener el historial: ' . ($response->json()['message'] ?? ''));
            }
        }

        return view('layouts.reports.driver-history', compact('drivers', 'results'));
    }
}