<?php

// App/Infrastructure/Services/ApiResponseService.php

namespace App\Infrastructure\Services;

use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Service: ApiResponseService
 *
 * Provides standardized JSON API responses for success and error cases.
 * Supports transforming Eloquent models, collections, and paginated data
 * into arrays suitable for API responses, including pagination metadata.
 */
class ApiResponseService
{
    /**
     * Transform input data into an array suitable for JSON response.
     */
    protected function transformData(mixed $data): array
    {
        if ($data instanceof PaginatorContract) {
            return $data->getCollection()
                ->map(fn ($item) => $this->transformData($item))
                ->toArray();
        }

        if ($data instanceof Collection) {
            return $data->map(fn ($item) => $this->transformData($item))
                ->toArray();
        }

        if (is_object($data) && method_exists($data, 'toArray')) {
            return $data->toArray();
        }

        return (array) $data;
    }

    /**
     * Return a standardized success JSON response.
     */
    public function success(
        mixed $data = [],
        string $message = 'Success',
        int $code = Response::HTTP_OK,
        array $headers = []
    ): JsonResponse {
        $payload = $this->transformData($data);

        $response = [
            'status' => 'success',
            'http_status' => Response::$statusTexts[$code] ?? 'OK',
            'message' => $message,
            'data' => $payload,
        ];

        if ($data instanceof PaginatorContract) {
            $response['meta'] = [
                'current_page' => $data->currentPage(),
                'per_page' => $data->perPage(),
                'total' => $data instanceof LengthAwarePaginator ? $data->total() : count($payload),
                'last_page' => $data instanceof LengthAwarePaginator ? $data->lastPage() : 1,
            ];
        }

        return response()->json($response, $code, $headers);
    }

    /**
     * Return a standardized error JSON response.
     *
     * Accepts either a Throwable or a string message.
     */
    public function error(Throwable|string $e, int $code = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        // Default values
        $message = 'Internal server error';
        $errors = [];

        // Handle Throwable
        if ($e instanceof Throwable) {
            if ($e instanceof ModelNotFoundException) {
                $code = Response::HTTP_NOT_FOUND;
                $message = $e->getMessage() ?: 'Resource not found';
            } elseif ($e instanceof \RuntimeException && $e->getCode()) {
                $code = $e->getCode();
                $message = $e->getMessage();
            } else {
                $message = $e->getMessage() ?: $message;
            }

            if (config('app.debug')) {
                $errors = [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTrace(),
                ];
            }
        } else {
            // Handle plain string
            $message = $e;
        }

        return response()->json([
            'status' => 'error',
            'http_status' => Response::$statusTexts[$code] ?? 'Unknown',
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}
