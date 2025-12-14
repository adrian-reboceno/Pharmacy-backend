<?php

namespace App\Helpers;

use Symfony\Component\HttpFoundation\Response;

class ApiResponse
{
    public static function success($data = null, ?string $message = null, int $statusCode = Response::HTTP_OK)
    {
        return response()->json([
            'status' => 'success',
            'http_status' => Response::$statusTexts[$statusCode],
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    public static function error(string $message, int $statusCode = Response::HTTP_BAD_REQUEST, $errors = null)
    {
        return response()->json([
            'status' => 'error',
            'http_status' => Response::$statusTexts[$statusCode],
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }
}
