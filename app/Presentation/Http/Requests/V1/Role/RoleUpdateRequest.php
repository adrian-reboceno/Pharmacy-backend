<?php
# app/Presentation/Http/Requests/V1/Role/RoleUpdateRequest.php

namespace App\Presentation\Http\Requests\V1\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;   

/**
 * Handles validation for updating an existing Role.
 *
 * This FormRequest validates the input required to update a Role,
 * ensuring that the 'name' field is provided, is a valid string, and 
 * unique in the Roles table (excluding the current Role being updated).
 *
 * Principles applied:
 * - SRP (Single Responsibility Principle): Only validates input for updating a Role.
 */
class RoleUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * You can extend this method to include authorization logic, 
     * such as checking if the user has the 'Role-edit' permission.
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
     * Rules:
     * - 'name' is required, must be a string, and unique in the 'roles' table,
     *   ignoring the current Role's ID.
     * - 'guard_name' is optional, but must be a string if provided.
     * - 'permissions' is required and must be an array.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                // Ensure the 'name' is unique, ignoring the current Role
                Rule::unique('roles', 'name')->ignore($this->route('id')),
            ],
            'guard_name' => 'nullable|string',
            'permissions' => 'required|array',
        ];
    }

    /**
     * Get custom error messages for validation failures.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string'   => 'The name must be a valid string.',
            'name.unique'   => 'A role with this name already exists.',
            'guard_name.string' => 'The guard_name must be a valid string.',
            'permissions.required' => 'The permissions field is required.',
            'permissions.array'    => 'The permissions must be a valid array.',
        ];
    }
}
