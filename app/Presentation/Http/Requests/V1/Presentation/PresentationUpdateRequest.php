<?php
# app/Presentation/Http/Requests/V1/Presentation/PresentationUpdateRequest.php

namespace App\Presentation\Http\Requests\V1\Presentation;

use Illuminate\Foundation\Http\FormRequest;

class PresentationUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string,mixed> */
    public function rules(): array
    {
        return [
            'name'        => ['sometimes', 'string', 'min:2', 'max:255'],
            'is_active'   => ['sometimes', 'boolean'],
        ];
    }
}