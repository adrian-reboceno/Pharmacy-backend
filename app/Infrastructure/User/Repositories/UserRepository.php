<?php
# app/Infrastructure/User/Repositories/UserRepository.php
namespace App\Infrastructure\User\Repositories;

use App\Domain\User\Entities\User as UserEntity;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Models\User as UserModel;

class UserRepository implements UserRepositoryInterface
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
