<?php

namespace App\Services;

use App\DTOs\UserDTO;

class AuthService
{
    public function login(string $email, string $password): array
    {
        $credentials = compact('email', 'password');

        if (!$token = auth()->attempt($credentials)) {
            throw new \Exception('Credenciales inválidas');
        }

        $user = auth()->user();

        $dto = new UserDTO(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            roles: $user->getRoleNames()->toArray(),
            permissions: $user->getAllPermissions()->pluck('name')->toArray()
        );

        return [
            'user'  => $dto->toArray(),
            'token' => $token,
        ];
    }
}