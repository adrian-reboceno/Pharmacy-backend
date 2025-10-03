<?php

namespace App\Infrastructure\Services;

use Illuminate\Support\Facades\Auth;
use App\Application\User\DTOs\V1\AutUserDTO as UserDTO;

class JwtAuthService
{
    /**
     * Intenta login y retorna token JWT
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
     * Renovar token JWT
     */
    public function refreshToken(): string
    {
        return Auth::refresh();
    }

    /**
     * Cerrar sesión
     */
    public function logout(): void
    {
        Auth::logout();
    }

    /**
     * Retorna el usuario autenticado
     */
    public function user()
    {
        return Auth::user();
    }

    /**
     * Genera la estructura de respuesta con token, usuario, roles y permisos
     */
    public function respondWithToken(
        string $token,
        string $message = 'Autenticación exitosa',
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
