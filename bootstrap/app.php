<?php

use App\Http\Middleware\FormatValidationErrors;
use App\Presentation\Http\Traits\ApiResponseTrait;
use Illuminate\Auth\AuthenticationException;
// use App\Traits\ApiResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api/v1.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Middleware global para errores de validación
        $middleware->append(FormatValidationErrors::class);
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (\Throwable $e) {
            $trait = new class
            {
                use ApiResponseTrait;
            };

            // Modelo no encontrado
            if ($e instanceof ModelNotFoundException) {
                return $trait->errorResponse(
                    'Recurso no encontrado',
                    [],
                    Response::HTTP_NOT_FOUND
                );
            }

            // No autenticado
            if ($e instanceof AuthenticationException) {
                return $trait->errorResponse(
                    'No autenticado',
                    [],
                    Response::HTTP_UNAUTHORIZED
                );
            }

            // Validaciones
            if (method_exists($e, 'errors')) {
                return $trait->errorResponse(
                    'Errores de validación',
                    $e->errors(),
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            // Cualquier otro error
            return $trait->errorResponse(
                'Error interno',
                ['exception' => $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        });
    })
    ->create();
