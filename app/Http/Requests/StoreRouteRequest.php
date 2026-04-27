<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRouteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'min:3', 'max:100'],
            'origin'      => ['required', 'string', 'min:3', 'max:150'],
            'destination' => ['required', 'string', 'min:3', 'max:150'],
            'distance'    => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'El nombre de la ruta es obligatorio.',
            'name.min'             => 'El nombre debe tener al menos 3 caracteres.',
            'name.max'             => 'El nombre no debe superar los 100 caracteres.',
            'origin.required'      => 'El punto de origen es obligatorio.',
            'origin.min'           => 'El origen debe tener al menos 3 caracteres.',
            'origin.max'           => 'El origen no debe superar los 150 caracteres.',
            'destination.required' => 'El punto de destino es obligatorio.',
            'destination.min'      => 'El destino debe tener al menos 3 caracteres.',
            'destination.max'      => 'El destino no debe superar los 150 caracteres.',
            'distance.numeric'     => 'La distancia debe ser un valor numérico.',
            'distance.min'         => 'La distancia no puede ser negativa.',
            'description.max'      => 'La descripción no debe superar los 255 caracteres.',
        ];
    }
}