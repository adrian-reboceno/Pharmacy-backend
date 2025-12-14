<?php

// app/Http/Middleware/Authenticate.php

namespace App\Http\Middleware;

use App\Traits\ApiResponseTrait;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Authenticate
 *
 * Custom authentication middleware for API requests.
 *
 * This middleware extends Laravel's default `Authenticate` class to:
 * - Return JSON responses instead of redirecting to the login route.
 * - Automatically attach authenticated user information (including role and permissions)
 *   to the request object for downstream use.
 *
 * This implementation is tailored for stateless API applications,
 * ensuring that unauthenticated requests are handled gracefully.
 */
class Authenticate extends Middleware
{
    use ApiResponseTrait;

    /**
     * Handle unauthenticated requests.
     *
     * Overrides the default Laravel behavior to return a JSON response
     * instead of attempting to redirect to a login route.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array<int, string>  $guards
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, array $guards)
    {
        return $this->errorResponse(
            message: 'Unauthenticated',
            statusCode: Response::HTTP_UNAUTHORIZED
        );
    }

    /**
     * Disable redirect behavior for APIs.
     *
     * This ensures that API routes never attempt to redirect users
     * when authentication fails.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    protected function redirectTo($request): ?string
    {
        return null;
    }

    /**
     * Handle an incoming request.
     *
     * Authenticates the request and, if the user is authenticated,
     * attaches the user's basic information, role, and permissions
     * to the request object for convenient access in controllers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  ...$guards
     * @return mixed
     */
    public function handle($request, \Closure $next, ...$guards)
    {
        $this->authenticate($request, $guards);

        if (Auth::check()) {
            $user = Auth::user();

            // Attach authenticated user data to the request
            $request->merge([
                'auth_user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role->name ?? null,
                    'permissions' => $user->role->permissions->pluck('name') ?? [],
                ],
            ]);
        }

        return $next($request);
    }
}
