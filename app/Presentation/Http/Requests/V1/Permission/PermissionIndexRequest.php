<?php
#app/Presentation/Http/Requests/V1/Permission/PermissionIndexRequest.php;
namespace App\Presentation\Http\Requests\V1\Permission;

use Illuminate\Foundation\Http\FormRequest;

class PermissionIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Puedes ajustar según tus políticas de autorización
        return true;
    }

    public function rules(): array
    {
        return [
            'per_page' => 'sometimes|integer|min:1',
            'page'     => 'sometimes|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'per_page.integer' => 'El parámetro per_page debe ser un número entero.',
            'per_page.min'     => 'El parámetro per_page debe ser al menos 1.',
            'page.integer'     => 'El parámetro page debe ser un número entero.',
            'page.min'         => 'El parámetro page debe ser al menos 1.',
        ];
    }
}
