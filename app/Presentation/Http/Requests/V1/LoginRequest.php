<?php

// app/Presentation/Http/Requests/V1/LoginRequest.php

namespace App\Presentation\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class LoginRequest
 *
 * Handles and validates incoming authentication (login) requests.
 *
 * This request ensures that the client provides the necessary credentials
 * — a valid email address and password — before passing the data to the
 * authentication layer. It belongs to the Presentation layer in a Clean
 * Architecture structure.
 */
class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * This method can include custom authorization logic.
     * Currently, all requests are authorized by default.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define the validation rules for the login request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ];
    }

    /**
     * Define custom validation error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => 'The email field is required.',
            'email.email' => 'Please provide a valid email address.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 6 characters long.',
        ];
    }
}
