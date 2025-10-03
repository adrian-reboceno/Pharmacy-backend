<?php
# App/Infrastructure/Services/ApiResponseService.php
namespace App\Infrastructure\Services;

use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
     *
     * Handles Eloquent models, collections, paginators, and generic arrays/objects.
     *
     * @param mixed $data Input data (model, collection, paginator, or array).
     *
     * @return array Transformed data as array.
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

        if (is_object($data) && method_exists($data, 'toArray')) {
            return $data->toArray();
        }

        return (array) $data;
    }

    /**
     * Return a standardized success JSON response.
     *
     * If the data is paginated, pagination metadata is included.
     *
     * @param mixed  $data    The response data (default: empty array).
     * @param string $message Response message (default: 'Success').
     * @param int    $code    HTTP status code (default: 200 OK).
     * @param array  $headers Optional HTTP headers.
     *
     * @return JsonResponse Standardized success response.
     */
    public function success(
        mixed $data = [],
        string $message = 'Success',
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

    /**
     * Return a standardized error JSON response from a Throwable.
     *
     * Determines HTTP status code based on exception type, including
     * ModelNotFoundException and RuntimeException.
     *
     * @param \Throwable $e The exception or error that occurred.
     *
     * @return JsonResponse Standardized error response.
     */
    public function error(\Throwable $e): JsonResponse
    {
        $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        $message = 'Internal error';

        if ($e instanceof ModelNotFoundException) {
            $code = Response::HTTP_NOT_FOUND;
            $message = $e->getMessage() ?: 'Resource not found';
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
