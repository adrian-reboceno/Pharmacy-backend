<?php
# app/Infrastructure/Auth/Repositories/AuthUserRepository.php
namespace App\Infrastructure\Auth\Repositories;

use App\Domain\Auth\Entities\User as UserEntity;
use App\Domain\Auth\Repositories\AuthRepositoryInterface;
use App\Models\User as UserModel;

class AuthUserRepository implements AuthRepositoryInterface
{
    public function findByEmail(string $email): ?UserEntity
    {
        $user = UserModel::where('email', $email)->first();
        if (!$user) return null;

        $role = $user->roles->first()?->name ?? null;
        $permissions = $user->getAllPermissions()->pluck('name')->toArray();

        return new UserEntity(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            role: $role,
            permissions: $permissions
        );
    }

    public function getUserPermissions(UserEntity $user): array
    {
        return $user->permissions;
    }

    public function getUserRole(UserEntity $user): ?string
    {
        return $user->role;
    }
}
