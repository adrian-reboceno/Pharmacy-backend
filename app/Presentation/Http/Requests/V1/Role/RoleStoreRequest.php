<?php

// app/Presentation/Http/Requests/V1/Role/RoleStoreRequest.php

namespace App\Presentation\Http\Requests\V1\Role;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Handles validation for creating a new Role.
 *
 * This FormRequest validates the input required to create a Role,
 * ensuring that:
 * - 'name' is present, a valid string, and does not exceed 150 characters.
 * - 'guard_name' is optional, but if provided, must be either 'api' or 'web'
 *   and not exceed 50 characters.
 * - 'permissions' is required and must be an array of permission IDs.
 *
 * Principles applied:
 * - SRP (Single Responsibility Principle): Only validates input for creating a Role.
 */
class RoleStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * Extend this method to include authorization logic, for example,
     * checking if the user has the 'Role-create' permission.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * Rules:
     * - 'name' is required, must be a string, and has a maximum length of 150.
     * - 'guard_name' is optional, must be a string, max length 50, and can only
     *   be 'api' or 'web' if provided.
     * - 'permissions' is required and must be an array.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:150',
            'guard_name' => 'nullable|string|max:50|in:api,web',
            'permissions' => 'required|array',
        ];
    }

    /**
     * Get custom error messages for validation failures.
     *
     * Provides user-friendly messages for each validation rule.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a valid string.',
            'name.max' => 'The name cannot exceed 150 characters.',

            'guard_name.string' => 'The guard_name must be a valid string.',
            'guard_name.max' => 'The guard_name cannot exceed 50 characters.',
            'guard_name.in' => 'The guard_name must be either "api" or "web".',

            'permissions.required' => 'The permissions field is required.',
            'permissions.array' => 'The permissions must be a valid array.',
        ];
    }
}
