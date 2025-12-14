<?php

// app/Domain/User/Repositories/UserRepositoryInterface.php

namespace App\Domain\User\Repositories;

use App\Domain\User\Entities\User;
use Illuminate\Database\Eloquent\Builder;

/**
 * Interface UserRepositoryInterface
 *
 * Defines the contract for user-related persistence operations
 * in the domain layer.
 *
 * This interface is part of the Domain layer and ensures that
 * any implementation (e.g., Eloquent, API, or in-memory storage)
 * can handle Users while keeping the domain decoupled from
 * infrastructure concerns.
 *
 * Responsibilities include:
 * - Fetching Users by email or ID
 * - Retrieving roles and permissions
 * - Creating, updating, and deleting Users
 * - Providing query builders for advanced filtering
 *
 * Implementations must respect the domain invariants and return
 * domain entities (User) rather than framework models directly.
 */
interface UserRepositoryInterface
{
    /**
     * Find a User by email.
     *
     * @return User|null Returns the User entity or null if not found.
     */
    public function findByEmail(string $email): ?User;

    /**
     * Get all roles assigned to a User.
     *
     * @return string[] Array of role names.
     */
    public function getUserRoles(User $user): array;

    /**
     * Get all permissions assigned to a User, either directly or via roles.
     *
     * @return string[] Array of permission names.
     */
    public function getUserPermissions(User $user): array;

    /**
     * Return a query builder for advanced queries.
     */
    public function query(): Builder;

    /**
     * Find a User by its unique identifier.
     */
    public function find(int $id): ?User;

    /**
     * Create a new User.
     *
     * @param  array  $data  User attributes including optional roles
     */
    public function create(array $data): User;

    /**
     * Update an existing User entity.
     *
     * @param  User  $user  The user entity to update
     * @param  array  $data  Attributes to update
     * @return User Updated User entity
     */
    public function update(User $user, array $data): User;

    /**
     * Update an existing User by ID.
     *
     * @param  int  $id  User ID
     * @param  array  $data  Attributes to update
     * @return User Updated User entity
     */
    public function updateById(int $id, array $data): User;

    /**
     * Delete a User entity.
     *
     * @return bool True if deletion succeeded
     */
    public function delete(User $user): bool;

    /**
     * Delete a User by ID.
     *
     * @return bool True if deletion succeeded
     */
    public function deleteById(int $id): bool;
}
