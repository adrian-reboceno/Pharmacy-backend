<?php
#app/Presentation/Http/Requests/V1/Permission/PermissionUpdateRequest.php;
namespace App\Presentation\Http\Requests\V1\Permission;

use Illuminate\Foundation\Http\FormRequest;

class PermissionUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'       => 'required|string|unique:permissions,name,' . $this->route('permission')->id,
            'guard_name' => 'nullable|string',
        ];
    }
}
