<?php #app/Http/Requests/V1/PermissionUpdateRequest.php


namespace App\Http\Requests\V1\Permission;

use Illuminate\Foundation\Http\FormRequest;

class PermissionUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('permission');

        return [
            'name' => 'required|string|unique:permissions,name,' . $id,
            'guard_name' => 'nullable|string|in:api,web',
        ];
    }
}
