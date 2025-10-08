<?php
# app/Infrastructure/Auth/Repositories/AuthUserRepository.php

namespace App\Infrastructure\Auth\Repositories;

use App\Domain\Auth\Entities\User as UserEntity;
use App\Domain\Auth\Repositories\AuthRepositoryInterface;
use App\Models\User as UserModel;
//use App\Infrastructure\User\Models\User as UserModel;

/**
 * Infrastructure Repository: AuthUserRepository
 *
 * Implementation of the AuthRepositoryInterface.
 * This repository bridges the Domain User entity with the persistence layer,
 * specifically the Eloquent User model. It provides methods to fetch users,
 * their roles, and their permissions from the database.
 */
class AuthUserRepository implements AuthRepositoryInterface
{
    /**
     * Find a user by their email address.
     *
     * Uses the Eloquent User model to retrieve a user record, and then maps
     * it into the domain User entity with roles and permissions included.
     *
     * @param string $email The email address to search for.
     *
     * @return UserEntity|null The mapped User entity, or null if not found.
     */
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

    /**
     * Retrieve the permissions of a domain User entity.
     *
     * Since the User entity already carries its permissions,
     * this method simply returns them.
     *
     * @param UserEntity $user The domain User entity.
     *
     * @return array List of permissions (strings).
     */
    public function getUserPermissions(UserEntity $user): array
    {
        return $user->permissions;
    }

    /**
     * Retrieve the role of a domain User entity.
     *
     * Since the User entity already carries its role,
     * this method simply returns it.
     *
     * @param UserEntity $user The domain User entity.
     *
     * @return string|null The user’s role, or null if none is assigned.
     */
    public function getUserRole(UserEntity $user): ?string
    {
        return $user->role;
    }
}
