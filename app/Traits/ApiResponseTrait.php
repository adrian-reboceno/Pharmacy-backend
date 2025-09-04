<?php
namespace App\Traits;

use Symfony\Component\HttpFoundation\Response;

trait ApiResponseTrait
{
    public function successResponse(array $data = [], string $message = 'Éxito', int $code = Response::HTTP_OK)
    {
        return response()->json([
            'status' => 'success',
            'http_status' => Response::$statusTexts[$code] ?? 'OK',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public function errorResponse(string $message = 'Error interno', array $errors = [], int $code = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        return response()->json([
            'status' => 'error',
            'http_status' => Response::$statusTexts[$code] ?? 'Error',
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}

