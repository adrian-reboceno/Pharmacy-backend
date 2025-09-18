<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use App\Infrastructure\Services\ApiResponseService;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    protected ApiResponseService $apiResponse;

    public function __construct(ApiResponseService $apiResponse)
    {
        parent::__construct(app());
        $this->apiResponse = $apiResponse;
    }

    /**
     * Mapeo de excepciones → mensaje + código HTTP
     */
    protected array $exceptionMap = [
        ModelNotFoundException::class => [
            'message' => 'Recurso no encontrado',
            'code'    => Response::HTTP_NOT_FOUND,
        ],
        AuthenticationException::class => [
            'message' => 'No autenticado',
            'code'    => Response::HTTP_UNAUTHORIZED,
        ],
        \App\Exceptions\V1\Permission\PermissionException::class => [
            'message' => 'Permiso denegado',
            'code'    => Response::HTTP_FORBIDDEN,
        ],
         \App\Presentation\Exceptions\V1\Auth\InvalidCredentialsException::class => [
            'message' => 'Credenciales inválidas',
            'code'    => Response::HTTP_UNAUTHORIZED,
        ],
    ];

    /**
     * Report or log an exception.
     */
    public function report(Throwable $exception): void
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        if ($request->is('api/*')) {
            foreach ($this->exceptionMap as $class => $config) {
                if ($exception instanceof $class) {
                    return $this->apiResponse->error(
                        $exception->getMessage() ?: $config['message'],
                        [],
                        $config['code']
                    );
                }
            }

            // Validación (captura especial)
            if ($exception instanceof ValidationException) {
                return $this->apiResponse->error(
                    'Errores de validación',
                    $exception->errors(),
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            // Cualquier otro error no mapeado
            return $this->apiResponse->error(
                'Error interno',
                ['exception' => $exception->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        // Para rutas web, usar render normal de Laravel
        return parent::render($request, $exception);
    }
}
