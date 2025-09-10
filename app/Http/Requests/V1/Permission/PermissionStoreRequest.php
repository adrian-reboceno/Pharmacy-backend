<?php #app/Http/Requests/V1/Permission/PermissionStoreRequest.php

namespace App\Http\Requests\V1\Permission;

use Illuminate\Foundation\Http\FormRequest;

class PermissionStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // puedes validar si el user autenticado puede crear
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:150',
            'guard_name' => 'nullable|string|max:50|in:api,web',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.string'   => 'El campo nombre debe ser un texto válido.',
            'name.max'      => 'El nombre no puede tener más de 150 caracteres.',
            'guard_name.string' => 'El guard debe ser un texto válido.',
            'guard_name.max'    => 'El guard no puede tener más de 50 caracteres.',
        ];
    }
}
