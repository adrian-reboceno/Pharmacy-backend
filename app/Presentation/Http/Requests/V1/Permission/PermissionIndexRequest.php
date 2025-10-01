<?php
# app/Presentation/Http/Requests/V1/Permission/PermissionIndexRequest.php

namespace App\Presentation\Http\Requests\V1\Permission;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Handles validation for listing permissions with optional pagination.
 *
 * This FormRequest ensures that the 'per_page' and 'page' query parameters,
 * if provided, are integers greater than or equal to 1.
 *
 * Applied principles:
 * - **SRP (Single Responsibility Principle):** Only validates input for listing permissions.
 */
class PermissionIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * You can adjust this according to your authorization policies.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string,string>
     */
    public function rules(): array
    {
        return [
            'per_page' => 'sometimes|integer|min:1',
            'page'     => 'sometimes|integer|min:1',
        ];
    }

    /**
     * Get custom error messages for validation failures.
     *
     * @return array<string,string>
     */
    public function messages(): array
    {
        return [
            'per_page.integer' => 'The per_page parameter must be an integer.',
            'per_page.min'     => 'The per_page parameter must be at least 1.',
            'page.integer'     => 'The page parameter must be an integer.',
            'page.min'         => 'The page parameter must be at least 1.',
        ];
    }
}
