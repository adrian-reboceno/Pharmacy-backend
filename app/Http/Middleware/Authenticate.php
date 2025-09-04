<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Authenticate extends Middleware
{
    use ApiResponseTrait;

    /**
     * Override: cuando no está autenticado retorna JSON,
     * nunca intenta redirigir a route('login').
     */
    protected function unauthenticated($request, array $guards)
    {
        return ApiResponse::error(
            message: 'No autenticado',
            statusCode: Response::HTTP_UNAUTHORIZED
        );
    }


    /**
     * Para APIs no redirige nunca.
     */
    protected function redirectTo($request): ?string
    {
        return null;
    }

    /**
     * ✅ Middleware handle: agrega datos del usuario
     * y sus permisos a la request si está autenticado.
     */
    public function handle($request, \Closure $next, ...$guards)
    {
        $this->authenticate($request, $guards);

        if (Auth::check()) {
            $user = Auth::user();

            // 🚀 Aquí asumimos que tu User tiene relación con roles/permisos
            // Ejemplo: $user->role->permissions
            $request->merge([
                'auth_user' => [
                    'id'        => $user->id,
                    'name'      => $user->name,
                    'email'     => $user->email,
                    'role'      => $user->role->name ?? null,
                    'permisos'  => $user->role->permissions->pluck('name') ?? [],
                ]
            ]);
        }

        return $next($request);
    }
}