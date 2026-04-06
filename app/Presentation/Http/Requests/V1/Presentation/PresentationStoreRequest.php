<?php
# app/Presentation/Http/Requests/V1/Presentation/PresentationStoreRequest.php

namespace App\Presentation\Http\Requests\V1\Presentation;

use Illuminate\Foundation\Http\FormRequest;

class PresentationStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string,mixed> */
    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'min:2', 'max:255'],           
            'is_active'   => ['sometimes', 'boolean'],
        ];
    }
}