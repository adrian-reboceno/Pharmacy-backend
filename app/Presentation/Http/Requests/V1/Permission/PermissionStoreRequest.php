<?php
# app/Presentation/Http/Requests/V1/Permission/PermissionStoreRequest.php

namespace App\Presentation\Http\Requests\V1\Permission;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Handles validation for creating a new permission.
 *
 * This FormRequest validates the input required to create a permission,
 * ensuring the 'name' is present and a valid string, and that the optional
 * 'guard_name' is either 'api' or 'web' if provided.
 *
 * Applied principles:
 * - **SRP (Single Responsibility Principle):** Only validates input for creating a permission.
 */
class PermissionStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * You can add additional authorization logic here (e.g., check if the user
     * has the 'permission-create' ability).
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
            'name'       => 'required|string|max:150',
            'guard_name' => 'nullable|string|max:50|in:api,web',
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
            'name.required'     => 'The name field is required.',
            'name.string'       => 'The name must be a valid string.',
            'name.max'          => 'The name cannot exceed 150 characters.',
            'guard_name.string' => 'The guard_name must be a valid string.',
            'guard_name.max'    => 'The guard_name cannot exceed 50 characters.',
            'guard_name.in'     => 'The guard_name must be either "api" or "web".',
        ];
    }
}
