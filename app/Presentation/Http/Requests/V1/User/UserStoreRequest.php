<?php

// app/Presentation/Http/Requests/V1/User/UserStoreRequest.php

namespace App\Presentation\Http\Requests\V1\User;

use App\Application\User\DTOs\V1\CreateUserDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Handles validation for creating a new User.
 *
 * This FormRequest ensures all required user fields are valid
 * before passing them to the application layer. It also converts
 * validated input into a CreateUserDTO instance.
 */
class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the current user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization logic can be implemented later
        return true;
    }

    /**
     * Define the validation rules for creating a user.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ];
    }

    /**
     * Convert validated data into a CreateUserDTO instance.
     */
    public function toDTO(): CreateUserDTO
    {
        return CreateUserDTO::fromArray($this->validated());
    }
}
