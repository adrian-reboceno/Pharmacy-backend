<?php
# app/Presentation/Http/Requests/V1/Category/CategoryIndexRequest.php

namespace App\Presentation\Http\Requests\V1\Category;

use Illuminate\Foundation\Http\FormRequest;

class CategoryIndexRequest extends FormRequest
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
