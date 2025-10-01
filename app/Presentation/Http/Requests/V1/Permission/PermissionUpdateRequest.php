<?php
# app/Presentation/Http/Requests/V1/Permission/PermissionUpdateRequest.php

namespace App\Presentation\Http\Requests\V1\Permission;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Handles validation for updating an existing permission.
 *
 * This FormRequest validates the input required to update a permission,
 * ensuring that the 'name' is provided, a valid string, and unique in the
 * permissions table (excluding the current permission being updated).
 *
 * Applied principles:
 * - **SRP (Single Responsibility Principle):** Only validates input for updating a permission.
 */
class PermissionUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * You can add additional authorization logic here, e.g., check if the
     * user has the 'permission-edit' ability.
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
     * - The 'name' field is required, must be a string, and unique in the
     *   permissions table, ignoring the current permission's ID.
     * - The 'guard_name' field is optional and must be a string if provided.
     *
     * @return array<string,string>
     */
    public function rules(): array
    {
        return [
            'name'       => 'required|string|unique:permissions,name,' . $this->route('permission')->id,
            'guard_name' => 'nullable|string',
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
            'name.required' => 'The name field is required.',
            'name.string'   => 'The name must be a valid string.',
            'name.unique'   => 'A permission with this name already exists.',
            'guard_name.string' => 'The guard_name must be a valid string.',
        ];
    }
}
