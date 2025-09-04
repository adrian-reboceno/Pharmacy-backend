<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LogoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // todos los usuarios autenticados pueden cerrar sesión
    }

    public function rules(): array
    {
        return [
            // opcional: si quieres manejar cierre por dispositivo
            // 'device_id' => 'sometimes|string'
        ];
    }

    public function messages(): array
    {
        return [
            // 'device_id.string' => 'El identificador del dispositivo debe ser texto',
        ];
    }
}