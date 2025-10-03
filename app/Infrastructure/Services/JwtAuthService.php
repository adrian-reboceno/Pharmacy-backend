<?php
# App/Infrastructure/Services/JwtAuthService.php

namespace App\Infrastructure\Services;

use Illuminate\Support\Facades\Auth;
use App\Application\User\DTOs\V1\AutUserDTO as UserDTO;

/**
 * Service: JwtAuthService
 *
 * Handles JWT-based authentication in the infrastructure layer.
 * Provides methods for login, token refresh, logout, retrieving
 * the authenticated user, and building standardized JWT responses.
 */
class JwtAuthService
{
    /**
     * Attempt to log in a user with the given credentials.
     *
     * Adds the user’s direct permissions as JWT claims.
     *
     * @param array $credentials Associative array with:
     *                           - 'email'    => string
     *                           - 'password' => string
     *
     * @return string|null Returns a JWT token string if authentication succeeds, or null if it fails.
     */
    public function attemptLogin(array $credentials): ?string
    {
        if (! $token = Auth::attempt($credentials)) {
            return null;
        }

        $claims = [
            'permissions' => Auth::user()->getDirectPermissions()->pluck('name')->toArray(),
        ];

        return Auth::claims($claims)->attempt($credentials);
    }

    /**
     * Refresh the current JWT token for the authenticated user.
     *
     * @return string The refreshed JWT token.
     */
    public function refreshToken(): string
    {
        return Auth::refresh();
    }

    /**
     * Log out the currently authenticated user.
     *
     * Invalidates the current JWT token.
     *
     * @return void
     */
    public function logout(): void
    {
        Auth::logout();
    }

    /**
     * Retrieve the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null The authenticated user model, or null if not authenticated.
     */
    public function user()
    {
        return Auth::user();
    }

    /**
     * Build a standardized authentication response including token, user info, roles, and permissions.
     *
     * @param string $token              JWT token string.
     * @param string $message            Optional message (default: 'Authentication successful').
     * @param bool   $includeRoles       Whether to include the user's roles (default: true).
     * @param bool   $includePermissions Whether to include the user's permissions (default: true).
     *
     * @return array Structured authentication response.
     */
    public function respondWithToken(
        string $token,
        string $message = 'Authentication successful',
        bool $includeRoles = true,
        bool $includePermissions = true
    ): array {
        $user = $this->user();
        $roles = $includeRoles ? $user->getRoleNames() : [];
        $permissions = $includePermissions ? $user->getAllPermissions()->pluck('name')->toArray() : [];

        $userDTO = UserDTO::fromModel($user);

        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
            'user' => $userDTO,
            'roles' => $roles,
            'permissions' => $permissions,
            'message' => $message,
        ];
    }
}
