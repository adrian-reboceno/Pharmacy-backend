<?php

// app/Presentation/Http/Requests/V1/User/UserIndexRequest.php

namespace App\Presentation\Http\Requests\V1\User;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Handles validation for listing Users.
 *
 * This FormRequest allows filtering, sorting, and pagination
 * parameters to be validated and normalized for use in the
 * application layer (e.g., ListUser UseCase).
 */
class UserIndexRequest extends FormRequest
{
    /**
     * Determine if the current user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define validation rules for listing users.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'exists:roles,name'],
            'sort_by' => ['nullable', 'string', 'in:name,email'],
            'sort_order' => ['nullable', 'string', 'in:asc,desc'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    /**
     * Returns normalized filter parameters.
     *
     * @return array<string, mixed>
     *
     * @example
     * $filters = $request->filters();
     */
    public function filters(): array
    {
        $validated = $this->validated();

        return [
            'search' => $validated['search'] ?? null,
            'role' => $validated['role'] ?? null,
            'sort_by' => $validated['sort_by'] ?? 'name',
            'sort_order' => $validated['sort_order'] ?? 'asc',
            'page' => $validated['page'] ?? 1,
            'per_page' => $validated['per_page'] ?? 15,
        ];
    }
}
