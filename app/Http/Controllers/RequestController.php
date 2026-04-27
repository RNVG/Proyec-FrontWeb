<?php
namespace App\Http\Controllers;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;    
use Illuminate\Http\Request;

class RequestController extends Controller
{

public function index(Request $request)
{
    $token = session('access_token');
    $apiUrl = config('services.academy_api.url');

    try {
        $vResponse = Http::withToken($token)->get("$apiUrl/api/vehicle", [
            'status_filter' => 'available' 
        ]);
        if ($vResponse->successful()) {
            $vehicles = $vResponse->json()['data']['data'] ?? [];
            
            return view('requests.catalog', compact('vehicles'));
        }
        
        return back()->with('error', 'No se pudo obtener la información de la API.');

    } catch (\Exception $e) {
        return back()->with('error', 'Error de conexión: ' . $e->getMessage());
    }
    }
    
    public function myRequests()
    {
        $token = session('access_token');
        $userId = session('auth_user')['id'];
        $apiUrl = config('services.academy_api.url');
        try {
            $response = Http::withToken($token)->get("$apiUrl/api/request");
            
            

            if ($response->successful()) {
                $data = $response->json()['data'] ?? [];
                $allRequests = collect($response->json()['data']['data'] ?? []);
                
                $requests = $allRequests->where('user_id', $userId);

                return view('requests.my_requests', compact('requests'));
            }
            return back()->with('error', 'No se pudieron obtener tus solicitudes.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error de conexión.');
        }
    }

    public function adminIndex()
    {
        $token = session('access_token');
        $apiUrl = config('services.academy_api.url') . '/api';

        try {
            // Pedimos los 3 listados
            $resReq = Http::withToken($token)->get("$apiUrl/request");
            $resUsers = Http::withToken($token)->get("$apiUrl/users");
            $resVeh = Http::withToken($token)->get("$apiUrl/vehicle");
            if ($resReq->successful() && $resUsers->successful() && $resVeh->successful()) {
                $users = collect($resUsers->json()['data']['data'])->keyBy('id');
                $vehicles = collect($resVeh->json()['data']['data'])->keyBy('id');
                
                $allRequests = collect($resReq->json()['data']['data'])->map(function ($item) use ($users, $vehicles) {
                    // Inyectamos nombres reales o el mensaje de error que acordamos
                    $item['user_name'] = $users->get($item['user_id'])['name'] ?? 'Usuario no encontrado 👤';
                    
                    $v = $vehicles->get($item['vehicle_id']);
                    $item['vehicle_info'] = $v ? "{$v['brand']} {$v['model']} ({$v['plate']})" : 'Vehículo no encontrado 🚗';
                    
                    return $item;
                });

                $pending = $allRequests->where('status', 'pending');
                $history = $allRequests->whereIn('status', ['approved', 'rejected']);

                return view('admin.request_index', compact('pending', 'history', 'users', 'vehicles'));
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error de conexión.');
        }
    }


 public function updateStatus(Request $request, $id)
{
    $token = session('access_token');
    $apiUrl = config('services.academy_api.url') . '/api';
    
    $adminId = session('auth_user')['id'] ?? null;
    $action = $request->input('action');
    $newStatus = ($action === 'approve') ? 'approved' : 'rejected';
    try {
        $response = Http::withToken($token)->put("$apiUrl/request/$id", [
            'status' => $newStatus,
            'approved_by' => $adminId,
        ]);
        if ($response->successful()) {
            return redirect()->route('admin_index')
                ->with('success', "Solicitud procesada correctamente.");
        }

        $errorMessage = $response->json()['message'] ?? 'Error inesperado al procesar la solicitud.';
        
        return back()->with('error', "No se pudo actualizar: $errorMessage");

    } catch (\Exception $e) {
        return back()->with('error', 'Error de conexión con la API 🔌');
    }
}


    public function cancel($id)
    {
        $token = session('access_token');
        $apiUrl = config('services.academy_api.url');

        try {
            $response = Http::withToken($token)->put("$apiUrl/api/request/$id", [
                'status' => 'rejected' 
            ]);

            if ($response->successful()) {
                return redirect()->route('requests.my_requests')->with('success', 'Solicitud cancelada.');
            }
            return back()->with('error', 'No se pudo cancelar la solicitud.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al procesar la cancelación.');
        }
    }
    public function create()
    {
        return view('requests.catalog');
    }
    public function update(Request $request, $id)
    {
        $token = session('access_token');
        $apiUrl = config('services.academy_api.url');

        try {
            $response = Http::withToken($token)->put("$apiUrl/api/request/$id", [
                'status' => 'rejected' 
            ]);

            if ($response->successful()) {
                return redirect()->route('requests.my_requests')->with('success', 'Solicitud actualizada.');
            }
            return back()->with('error', 'No se pudo actualizar la solicitud.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al procesar la actualización.');
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
        $statusOption = 'pending';
        $userId = session('auth_user')['id'];
         // Valor por defecto para choferes (role_id 3)
        try {
            if(session('role_id') != 3){
              $statusOption = 'approved'; 
            }
            $datosParaEnviar = [
                'user_id'      => $userId, 
                'vehicle_id'   => $request->vehicle_id,
                'start_date'   => $request->start_date,
                'end_date'     => $request->end_date,
                'observations' => $request->observations,
                'status'       => $statusOption,
            ];
        $response = Http::withToken($token)->post(config('services.academy_api.url') . "/api/request", $datosParaEnviar);
            if ($response->successful()) {
                return redirect()->route('admin_index')->with('success', 'Solicitud creada con éxito.');
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
