<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'plate'     => 'required|string|max:15',
            'brand'     => 'required|string|max:50',
            'model'     => 'required|string|max:50',
            'year'      => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'capacity'  => 'required|integer|min:1',
            'fuel_type' => 'sometimes|string',
            'status'    => 'required|string',
            'image'     => 'sometimes|image|mimes:jpeg,png,jpg|max:2048', // 2MB máximo
        ];
    }

    /**xss
     * Mensajes personalizados de error (Opcional).
     */
    public function messages(): array
    {
        return [
            'plate.required' => 'La placa es un campo obligatorio.',
            'year.max'       => 'El año no puede ser superior al próximo año.',
            'image.image'    => 'El archivo debe ser una imagen válida.',
        ];
    }
}
