<?php

// app/Presentation/Http/Requests/V1/User/UserUpdateRequest.php

namespace App\Presentation\Http\Requests\V1\User;

use App\Application\User\DTOs\V1\UpdateUserDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Handles validation for updating an existing User.
 *
 * This FormRequest supports partial updates and ensures
 * that any provided fields are valid. It also exposes a helper
 * method to transform validated data into an UpdateUserDTO.
 */
class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to perform this action.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define validation rules for updating a user.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $userId = $this->route('id') ?? $this->route('user');

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => [
                'sometimes', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => ['sometimes', 'string', 'min:8'],
            'roles' => ['sometimes', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ];
    }

    /**
     * Convert validated input into an UpdateUserDTO instance.
     */
    public function toDTO(): UpdateUserDTO
    {
        return UpdateUserDTO::fromArray($this->validated());
    }
}
