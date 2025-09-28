<?php
# app/Infrastructure/Repositories/RoleRepository.php

namespace App\Infrastructure\Repositories;

use App\Domain\Role\Repositories\RoleRepositoryInterface;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Builder;

/**
 * Eloquent implementation of the RoleRepositoryInterface.
 *
 * This repository provides persistence operations for roles using
 * the Spatie\Permission `Role` model. It bridges the domain layer
 * with the infrastructure layer, ensuring that higher-level modules
 * depend only on abstractions.
 *
 * Applied principles:
 * - **SRP (Single Responsibility Principle):** Responsible only for role persistence operations.
 * - **DIP (Dependency Inversion Principle):** Implements the domain-defined interface
 *   (`RoleRepositoryInterface`), allowing the application layer to remain decoupled
 *   from the underlying database implementation.
 */
class RoleRepository implements RoleRepositoryInterface
{
    /**
     * Returns a query builder for roles.
     *
     * @return Builder Query builder instance for roles.
     */
    public function query(): Builder
    {
        return Role::query();
    }

    /**
     * Finds a role by its unique identifier.
     *
     * @param int $id Unique identifier of the role.
     * @return Role|null Role instance if found, null otherwise.
     */
    public function find(int $id): ?object
    {
        return Role::find($id);
    }

    /**
     * Creates a new role.
     *
     * @param array $data Associative array containing role attributes.
     * @return Role The newly created role instance.
     */
    public function create(array $data): object
    {
        return Role::create($data);
    }

    /**
     * Updates an existing role by its ID.
     *
     * @param int $id Unique identifier of the role to update.
     * @param array $data Associative array of updated role attributes.
     * @return Role The updated role instance.
     */
    public function update(int $id, array $data): object
    {
        $role = $this->find($id);
        $role->update($data);

        return $role;
    }

    /**
     * Deletes a role by its unique identifier.
     *
     * @param int $id Unique identifier of the role to delete.
     * @return bool True if the role was successfully deleted, false otherwise.
     */
    public function delete(int $id): bool
    {
        $role = $this->find($id);

        return $role ? $role->delete() : false;
    }

    /**
     * Checks if a role with the given name and guard already exists.
     *
     * Typically used in the **CreateRole** use case to prevent duplicates.
     *
     * @param string $name Role name to check.
     * @param string $guard_name Guard name associated with the role.
     * @return bool True if a role with the same name and guard exists, false otherwise.
     */
    public function exists(string $name, string $guard_name): bool
    {
        return $this->query()
            ->where('name', $name)
            ->where('guard_name', $guard_name)
            ->exists();
    }

    /**
     * Checks if a role with the given name and guard already exists,
     * excluding a specific role ID.
     *
     * Typically used in the **UpdateRole** use case to ensure uniqueness
     * without conflicting with the current role being updated.
     *
     * @param string $name Role name to check.
     * @param string $guard_name Guard name associated with the role.
     * @param int $exceptId Role ID to exclude from the check.
     * @return bool True if another role with the same name and guard exists, false otherwise.
     */
    public function existsExceptId(string $name, string $guard_name, int $exceptId): bool
    {
        return $this->query()
            ->where('name', $name)
            ->where('guard_name', $guard_name)
            ->where('id', '!=', $exceptId)
            ->exists();
    }
}
