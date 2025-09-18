<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Presentation\Http\Traits\ApiResponseTrait;

class FormatValidationErrors
{
    use ApiResponseTrait;

    public function handle(Request $request, Closure $next)
    {
        try {
            return $next($request);
        } catch (ValidationException $e) {
            return $this->errorResponse(
                'Errores de validación',
                $e->errors(),
                422
            );
        }
    }
}