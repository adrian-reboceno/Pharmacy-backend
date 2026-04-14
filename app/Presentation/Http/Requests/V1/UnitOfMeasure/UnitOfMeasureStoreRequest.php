<?php

# app/Presentation/Http/Requests/V1/UnitOfMeasure/UnitOfMeasureStoreRequest.php

namespace App\Presentation\Http\Requests\V1\UnitOfMeasure;

use Illuminate\Foundation\Http\FormRequest;

class UnitOfMeasureStoreRequest extends FormRequest
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
            'symbol'      => ['required', 'string', 'min:1', 'max:10'],
            'is_active'   => ['sometimes', 'boolean'],
        ];
    }
}