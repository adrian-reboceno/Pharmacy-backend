<?php
# app/Presentation/Http/Requests/V1/LogoutRequest.php

namespace App\Presentation\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class LogoutRequest
 *
 * Handles and validates logout requests from authenticated users.
 *
 * This request is responsible for authorizing and optionally validating
 * the data provided when a user logs out. It belongs to the Presentation
 * layer within a Clean Architecture structure.
 *
 * Optionally, this request can include additional fields such as
 * `device_id` if the application supports session management by device.
 *
 * @package App\Presentation\Http\Requests\V1
 */
class LogoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * By default, all authenticated users are allowed to log out.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // All authenticated users can log out
    }

    /**
     * Define the validation rules for the logout request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            // Optional: Uncomment if logout should be handled by device
            // 'device_id' => 'sometimes|string',
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
            // 'device_id.string' => 'The device identifier must be a string.',
        ];
    }
}
