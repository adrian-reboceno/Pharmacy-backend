<?php
# app/Presentation/Http/Requests/V1/Permission/PermissionUpdateRequest.php

namespace App\Presentation\Http\Requests\V1\Permission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Handles validation for updating an existing Permission.
 *
 * This FormRequest validates the input required to update a Permission,
 * ensuring that the 'name' field is provided, is a valid string, and unique
 * in the permissions table (excluding the current Permission being updated).
 *
 * Principles applied:
 * - SRP (Single Responsibility Principle): Only validates input for updating a Permission.
 */
class PermissionUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * Extend this method to include authorization logic, for example,
     * checking if the user has the 'permission-edit' ability.
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
     * - 'name' is required, must be a string, and unique in the 'permissions' table,
     *   ignoring the current Permission's ID.
     * - 'guard_name' is optional, but if provided, must be a string.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                // Ensure the 'name' is unique, ignoring the current permission
                Rule::unique('permissions', 'name')->ignore($this->route('id')),
            ],
            'guard_name' => 'nullable|string',
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
            'name.string'   => 'The name must be a valid string.',
            'name.unique'   => 'A permission with this name already exists.',
            'guard_name.string' => 'The guard_name must be a valid string.',
        ];
    }
}
