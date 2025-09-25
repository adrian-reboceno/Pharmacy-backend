<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Infrastructure\Services\ApiResponseService;


class Handler extends ExceptionHandler
{
    /**
     * Report or log an exception.
     */
    public function report(Throwable $e): void
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     */
   public function render($request, Throwable $e)
    {
        $apiResponse = app(ApiResponseService::class);

        if ($request->is('api/*')) {

            // 1️⃣ Cualquier HttpException o subclase (incluye PermissionException)
            if ($e instanceof HttpExceptionInterface) {
                return $apiResponse->error(
                    $e->getMessage(),
                    [],
                    $e->getStatusCode()
                );
            }

            // 2️⃣ Validación
            if ($e instanceof ValidationException) {
                return $apiResponse->error(
                    'Errores de validación',
                    $e->errors(),
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            // 3️⃣ Model not found
            if ($e instanceof ModelNotFoundException) {
                return $apiResponse->error(
                    'Recurso no encontrado',
                    [],
                    Response::HTTP_NOT_FOUND
                );
            }

            // 4️⃣ Autenticación
            if ($e instanceof AuthenticationException) {
                return $apiResponse->error(
                    'No autenticado',
                    [],
                    Response::HTTP_UNAUTHORIZED
                );
            }
            if ($exception instanceof App\Domain\Permission\Exceptions\PermissionException) {
                return $this->errorResponse(
                    $exception->getMessage(),
                    [],
                    $exception->getCode() ?: Response::HTTP_BAD_REQUEST
                );
            }

            // 5️⃣ Cualquier otro error → 500
            /*return $apiResponse->error(
                'Error interno',
                ['exception' => $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );*/
        }

        return parent::render($request, $e);
    }
}
