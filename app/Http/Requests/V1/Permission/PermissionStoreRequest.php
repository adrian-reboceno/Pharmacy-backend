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
            'name' => 'required|string|unique:permissions,name',
            'guard_name' => 'nullable|string|in:api,web',
        ];
    }
}
