<?php
# app/Domain/Auth/Repositories/AuthRepositoryInterface.php

namespace App\Domain\Auth\Repositories;

use App\Domain\Auth\Entities\User;

/**
 * Interface AuthRepositoryInterface
 *
 * Defines the contract for authentication-related persistence operations
 * in the domain layer. Implementations of this interface are responsible
 * for retrieving and managing user data (roles, permissions, etc.)
 * from the underlying data source (e.g., database).
 */
interface AuthRepositoryInterface
{
    /**
     * Find a user by their email address.
     *
     * @param string $email The email address of the user.
     *
     * @return User|null Returns the User entity if found, or null if no user exists with the given email.
     */
    public function findByEmail(string $email): ?User;

    /**
     * Retrieve the permissions assigned to a user.
     *
     * @param User $user The domain User entity.
     *
     * @return array List of permissions (strings) granted to the user.
     */
    public function getUserPermissions(User $user): array;

    /**
     * Retrieve the role assigned to a user.
     *
     * @param User $user The domain User entity.
     *
     * @return string|null The user’s role, or null if no role is assigned.
     */
    public function getUserRole(User $user): ?string;
}
