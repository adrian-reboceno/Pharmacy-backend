<?php

namespace App\Infrastructure\Services;

use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ApiResponseService
{
    protected function transformData(mixed $data): array
    {
        if ($data instanceof PaginatorContract) {
            return $data->getCollection()
                        ->map(fn($item) => $this->transformData($item))
                        ->toArray();
        }

        if ($data instanceof Collection) {
            return $data->map(fn($item) => $this->transformData($item))
                        ->toArray();
        }

        if (is_object($data) && method_exists($data, 'toArray')) {
            return $data->toArray();
        }

        return (array) $data;
    }

    public function success(
        mixed $data = [],
        string $message = 'Éxito',
        int $code = Response::HTTP_OK,
        array $headers = []
    ): JsonResponse {
        $payload = $this->transformData($data);

        $response = [
            'status'      => 'success',
            'http_status' => Response::$statusTexts[$code] ?? 'OK',
            'message'     => $message,
            'data'        => $payload,
        ];

        if ($data instanceof PaginatorContract) {
            $response['meta'] = [
                'current_page' => $data->currentPage(),
                'per_page'     => $data->perPage(),
                'total'        => $data instanceof LengthAwarePaginator ? $data->total() : count($payload),
                'last_page'    => $data instanceof LengthAwarePaginator ? $data->lastPage() : 1,
            ];
        }

        return response()->json($response, $code, $headers);
    }

    public function error(\Throwable $e): JsonResponse
    {
        $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        $message = 'Error interno';

        if ($e instanceof ModelNotFoundException) {
            $code = Response::HTTP_NOT_FOUND;
            $message = $e->getMessage() ?: 'Recurso no encontrado';
        } elseif ($e instanceof \RuntimeException) {
            $code = $e->getCode() === 409 ? Response::HTTP_CONFLICT : $code;
            $code = $e->getCode() === 204 ? Response::HTTP_NO_CONTENT : $code;
            $code = $e->getCode() === 404 ? Response::HTTP_NOT_FOUND : $code;
            $message = $e->getMessage();
        }

        return response()->json([
            'status'      => 'error',
            'http_status' => Response::$statusTexts[$code] ?? 'unknown',
            'message'     => $message,
            'errors'      => [],
        ], $code);
    }
}
