<?php
# app/Presentation/Http/Requests/V1/Laboratory/LaboratoryUpdateRequest.php

namespace App\Presentation\Http\Requests\V1\Laboratory;

use Illuminate\Foundation\Http\FormRequest;

class LaboratoryUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string,mixed> */
    public function rules(): array
    {
        return [
            'name'      => ['sometimes', 'string', 'min:2', 'max:255'],
            'country'   => ['sometimes', 'string', 'min:2', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}