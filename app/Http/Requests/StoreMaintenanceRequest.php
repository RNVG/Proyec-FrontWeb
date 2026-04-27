<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaintenanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vehicle_id'  => ['required', 'integer'],
            'type'        => ['required', 'string', 'max:20'],
            'start_date'  => ['required', 'date'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:start_date'],
            'description' => ['required', 'string'],
            'cost'        => ['required', 'numeric', 'min:0'],
            'status'      => ['required', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'vehicle_id.required'   => 'Debe seleccionar un vehículo.',
            'vehicle_id.integer'    => 'El vehículo debe ser un identificador válido.',
            'type.required'         => 'El tipo de mantenimiento es obligatorio.',
            'type.max'              => 'El tipo no debe superar los 20 caracteres.',
            'start_date.required'   => 'La fecha de inicio es obligatoria.',
            'start_date.date'       => 'La fecha de inicio no es válida.',
            'end_date.date'         => 'La fecha de fin no es válida.',
            'end_date.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la de inicio.',
            'description.required'  => 'La descripción es obligatoria.',
            'cost.required'         => 'El costo es obligatorio.',
            'cost.numeric'          => 'El costo debe ser un valor numérico.',
            'cost.min'              => 'El costo no puede ser negativo.',
            'status.required'       => 'El estado es obligatorio.',
            'status.max'            => 'El estado no debe superar los 20 caracteres.',
        ];
    }
}