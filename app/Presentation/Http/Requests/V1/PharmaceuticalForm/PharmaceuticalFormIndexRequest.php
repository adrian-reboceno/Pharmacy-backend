<?php
# app/Presentation/Http/Requests/V1/PharmaceuticalForm/PharmaceuticalFormIndexRequest.php

namespace App\Presentation\Http\Requests\V1\PharmaceuticalForm;

use Illuminate\Foundation\Http\FormRequest;

class PharmaceuticalFormIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string,mixed> */
    public function rules(): array
    {
        return [
            'per_page' => 'sometimes|integer|min:1',
            'page'     => 'sometimes|integer|min:1',
            'name'     => ['sometimes', 'string', 'min:1'],
        ];
    }
}