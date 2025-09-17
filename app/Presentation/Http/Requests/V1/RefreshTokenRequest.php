<?php

#app/Presentation/Http/Requests/V1/RefreshTokenRequest.php

namespace App\Presentation\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class RefreshTokenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // si está autenticado, puede refrescar su token
    }

    public function rules(): array
    {
        return [
            // opcional: podrías exigir el refresh_token explícito
            // 'refresh_token' => 'required|string'
        ];
    }

    public function messages(): array
    {
        return [
            // 'refresh_token.required' => 'El token de refresco es obligatorio',
        ];
    }
}
