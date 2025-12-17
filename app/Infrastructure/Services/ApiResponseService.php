<?php

namespace App\Infrastructure\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

class ApiResponseService
{
    public function success(
        mixed $data = [],
        string $message = 'OK',
        int $code = Response::HTTP_OK,
    ): JsonResponse {
        return response()->json([
            'status'      => 'success',
            'http_status' => Response::$statusTexts[$code] ?? 'OK',
            'message'     => $message,
            'data'        => $data,
        ], $code);
    }

    public function error(
        Throwable|string $error,
        int $code = Response::HTTP_INTERNAL_SERVER_ERROR,
        ?array $errors = null,
    ): JsonResponse {
        $message = is_string($error) ? $error : 'Error interno';

        $payloadErrors = $errors ?? [];

        if ($error instanceof Throwable && config('app.debug')) {
            $payloadErrors['exception'] = $error->getMessage();
            $payloadErrors['file']      = $error->getFile();
            $payloadErrors['line']      = $error->getLine();
        }

        return response()->json([
            'status'      => 'error',
            'http_status' => Response::$statusTexts[$code] ?? 'Error',
            'message'     => $message,
            'errors'      => $payloadErrors,
        ], $code);
    }
}
