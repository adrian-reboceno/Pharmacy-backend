<?php

# app/Infrastructure/Services/ApiResponseService.php
namespace App\Infrastructure\Services;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ApiResponseService
{
    /**
     * Convierte cualquier dato en array listo para API
     * Detecta: DTO, Collection, Paginator, Model
     */
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

        // DTO o Modelo Eloquent
        if (is_object($data) && method_exists($data, 'toArray')) {
            return $data->toArray();
        }

        // Arrays planos o valores simples
        return (array) $data;
    }

    /**
     * Respuesta exitosa uniforme
     */
    public function success(
        mixed $data = [],
        string $message = 'Éxito',
        int $code = Response::HTTP_OK,
        array $headers = []
    ): \Illuminate\Http\JsonResponse {
        $payload = $this->transformData($data);

        $response = [
            'status'      => 'success',
            'http_status' => Response::$statusTexts[$code] ?? 'OK',
            'message'     => $message,
            'data'        => $payload,
        ];

        // Meta si es paginación
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

    /**
     * Respuesta de error uniforme
     */
    public function error(
        string $message = 'Error interno',
        array $errors = [],
        int $code = Response::HTTP_INTERNAL_SERVER_ERROR,
        array $headers = []
    ): \Illuminate\Http\JsonResponse {
        return response()->json([
            'status'      => 'error',
            'http_status' => Response::$statusTexts[$code] ?? 'Error',
            'message'     => $message,
            'errors'      => $errors,
        ], $code, $headers);
    }
}
