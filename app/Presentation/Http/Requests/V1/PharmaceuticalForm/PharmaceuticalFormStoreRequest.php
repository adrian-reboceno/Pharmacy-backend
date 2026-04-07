<?php
# app/Presentation/Http/Requests/V1/PharmaceuticalForm/PharmaceuticalFormStoreRequest.php

namespace App\Presentation\Http\Requests\V1\PharmaceuticalForm;

use Illuminate\Foundation\Http\FormRequest;

class PharmaceuticalFormStoreRequest extends FormRequest
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