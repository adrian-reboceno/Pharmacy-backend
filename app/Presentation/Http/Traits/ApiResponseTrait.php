<?php

namespace App\Presentation\Http\Traits;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

trait ApiResponseTrait
{
    /**
     * Respuesta exitosa uniforme para la API
     */
    public function successResponse(
        array|LengthAwarePaginator|Paginator $data = [],
        string $message = 'Éxito',
        int $code = Response::HTTP_OK
    ) {
        $response = [
            'status'      => 'success',
            'http_status' => Response::$statusTexts[$code] ?? 'OK',
            'message'     => $message,
            'data'        => $data,
        ];

        // Si es una respuesta paginada, añadimos metadatos
        if ($data instanceof LengthAwarePaginator || $data instanceof Paginator) {
            $response['data'] = $data->items();
            $response['meta'] = [
                'current_page' => $data->currentPage(),
                'per_page'     => $data->perPage(),
                'total'        => $data instanceof LengthAwarePaginator ? $data->total() : null,
                'last_page'    => $data instanceof LengthAwarePaginator ? $data->lastPage() : null,
            ];
        }

        return response()->json($response, $code);
    }

    /**
     * Respuesta de error uniforme para la API
     */
    public function errorResponse(
        string $message = 'Error interno',
        array $errors = [],
        int $code = Response::HTTP_INTERNAL_SERVER_ERROR
    ) {
        return response()->json([
            'status'      => 'error',
            'http_status' => Response::$statusTexts[$code] ?? 'Error',
            'message'     => $message,
            'errors'      => !empty($errors) ? $errors : null,
        ], $code);
    }
}
