<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user'); // Para edición

        $rules = [
            'name'      => ['required', 'string', 'min:3', 'max:100'],
            'email'     => ['required', 'email', 'max:150'],
            'telephone' => ['nullable', 'string', 'min:8', 'max:25'],
            'role_id'   => ['required', 'integer', 'in:1,2,3'],
        ];

        if ($this->isMethod('post')) {
            $rules['password'] = ['required', 'string', 'min:8', 'max:72'];
        } else {
            $rules['password'] = ['nullable', 'string', 'min:8', 'max:72'];
        }

        // No usamos unique local, la API lo valida

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'El nombre es obligatorio.',
            'name.min'           => 'El nombre debe tener al menos 3 caracteres.',
            'name.max'           => 'El nombre no debe superar los 100 caracteres.',

            'email.required'     => 'El correo es obligatorio.',
            'email.email'        => 'Debe ingresar un correo válido.',
            'email.max'          => 'El correo no debe superar los 150 caracteres.',

            'telephone.min'      => 'El teléfono debe tener al menos 8 dígitos.',
            'telephone.max'      => 'El teléfono no debe superar los 25 caracteres.',

            'password.required'  => 'La contraseña es obligatoria.',
            'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
            'password.max'       => 'La contraseña no debe superar los 72 caracteres.',

            'role_id.required'   => 'Debe seleccionar un rol.',
            'role_id.in'         => 'El rol seleccionado no es válido.',
        ];
    }
}