<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait JwtAuthHelpers
{
    public function respondWithToken($token, string $message = 'Autenticación exitosa', bool $includeRoles = true, bool $includePermissions = true)
    {
        $user = Auth::user();
        $roles = $includeRoles ? $user->getRoleNames() : [];
        $permissions = $includePermissions ? $user->getAllPermissions()->pluck('name') : [];

        return $this->successResponse([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
            'user' => $user,
            'roles' => $roles,
            'permissions' => $permissions,
        ], $message);
    }
}