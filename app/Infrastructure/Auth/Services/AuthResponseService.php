<?php
# app/Infrastructure/Auth/Services/AuthResponseService.php

namespace App\Infrastructure\Auth\Services;

use App\Application\Auth\DTOs\V1\LoginResultDTO;
use App\Domain\User\Entities\User as DomainUser;
use App\Presentation\DTOs\V1\User\UserResponseDTO;
use App\Infrastructure\User\Models\User as EloquentUser; // 👈 AQUÍ el modelo correcto

final class AuthResponseService
{
    public function buildLoginPayload(LoginResultDTO $result): array
    {
        $domainUser = $result->user;
        $userDTO    = UserResponseDTO::fromEntity($domainUser);

        $eloquentUser = EloquentUser::find($domainUser->id()->value());

        $roles       = [];
        $permissions = [];

        if ($eloquentUser) {
            if (method_exists($eloquentUser, 'getRoleNames')) {
                $roles = $eloquentUser->getRoleNames()->toArray();
            }

            if (method_exists($eloquentUser, 'getAllPermissions')) {
                $permissions = $eloquentUser
                    ->getAllPermissions()
                    ->pluck('name')
                    ->toArray();
            }
        }

        return [
            'access_token' => $result->accessToken,
            'token_type'   => $result->tokenType,
            'expires_in'   => $result->expiresIn,
            'user'         => $userDTO->toArray(),
            'roles'        => $roles,
            'permissions'  => $permissions,
        ];
    }

    public function buildUserProfilePayload(DomainUser $domainUser): array
    {
        $userDTO      = UserResponseDTO::fromEntity($domainUser);
        $eloquentUser = EloquentUser::find($domainUser->id()->value());

        $roles       = [];
        $permissions = [];

        if ($eloquentUser) {
            if (method_exists($eloquentUser, 'getRoleNames')) {
                $roles = $eloquentUser->getRoleNames()->toArray();
            }

            if (method_exists($eloquentUser, 'getAllPermissions')) {
                $permissions = $eloquentUser
                    ->getAllPermissions()
                    ->pluck('name')
                    ->toArray();
            }
        }

        return [
            'user'        => $userDTO->toArray(),
            'roles'       => $roles,
            'permissions' => $permissions,
        ];
    }
}
