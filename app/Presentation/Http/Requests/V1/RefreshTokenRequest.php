<?php
# app/Presentation/Http/Requests/V1/RefreshTokenRequest.php

namespace App\Presentation\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RefreshTokenRequest
 *
 * Handles and validates token refresh requests from authenticated users.
 *
 * This request is part of the authentication flow, allowing users
 * to obtain a new access token when their current one expires.
 * It ensures that the request structure is valid before passing
 * it to the application or domain layers.
 *
 * Optionally, you may include a `refresh_token` field in the validation
 * rules if your application requires it explicitly.
 *
 * @package App\Presentation\Http\Requests\V1
 */
class RefreshTokenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * By default, any authenticated user can refresh their token.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // Authenticated users can refresh their token
    }

    /**
     * Define the validation rules for the refresh token request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            // Optional: Uncomment if a refresh token is required
            // 'refresh_token' => 'required|string',
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
            // 'refresh_token.required' => 'The refresh token is required.',
        ];
    }
}
