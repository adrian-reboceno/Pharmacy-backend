<?php
#namespace App\Http\Requests\V1\Permission;
namespace App\Http\Requests\V1\Permission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PermissionIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Permite el acceso a todos, el middleware se encarga de permisos
        return true;
    }

    public function rules(): array
    {
        return [
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'], // máximo 100 por página
            'page'     => ['sometimes', 'integer', 'min:1'],            // mínimo página 1
        ];
    }

    public function messages(): array
    {
        return [
            'per_page.integer' => 'El parámetro per_page debe ser un número entero.',
            'per_page.min'     => 'El parámetro per_page debe ser al menos 1.',
            'per_page.max'     => 'El parámetro per_page no puede superar 100.',
            'page.integer'     => 'El parámetro page debe ser un número entero.',
            'page.min'         => 'El parámetro page debe ser al menos 1.',
        ];
    }

    // Para devolver errores JSON usando ApiResponseTrait
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = response()->json([
            'status'      => 'error',
            'http_status' => 'Unprocessable Entity',
            'message'     => 'Errores de validación',
            'errors'      => $validator->errors()
        ], 422);

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
