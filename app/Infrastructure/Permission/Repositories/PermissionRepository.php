<?php

// app/Infrastructure/Permission/Repositories/PermissionRepository.php

namespace App\Infrastructure\Permission\Repositories;

use App\Domain\Permission\Repositories\PermissionRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Permission;

/**
 * Eloquent implementation of the PermissionRepositoryInterface.
 *
 * This repository provides persistence operations for permissions using
 * the Spatie\Permission `Permission` model. It bridges the domain layer
 * with the infrastructure layer, ensuring that higher-level modules
 * depend only on abstractions.
 *
 * Applied principles:
 * - **SRP (Single Responsibility Principle):** Responsible only for permission persistence operations.
 * - **DIP (Dependency Inversion Principle):** Implements the domain-defined interface
 *   (`PermissionRepositoryInterface`), allowing the application layer to remain decoupled
 *   from the underlying database implementation.
 */
class PermissionRepository implements PermissionRepositoryInterface
{
    /**
     * Returns a query builder for permissions.
     *
     * @return Builder Query builder instance for permissions.
     */
    public function query(): Builder
    {
        return Permission::query();
    }

    /**
     * Finds a permission by its unique identifier.
     *
     * @param  int  $id  Unique identifier of the permission.
     * @return Permission|null Permission instance if found, null otherwise.
     */
    public function find(int $id): ?object
    {
        return Permission::find($id);
    }

    /**
     * Creates a new permission.
     *
     * @param  array  $data  Associative array containing permission attributes.
     * @return Permission The newly created permission instance.
     */
    public function create(array $data): object
    {
        return Permission::create($data);
    }

    /**
     * Updates an existing permission by its ID.
     *
     * @param  int  $id  Unique identifier of the permission to update.
     * @param  array  $data  Associative array of updated permission attributes.
     * @return Permission The updated permission instance.
     */
    public function update(int $id, array $data): object
    {
        $permission = $this->find($id);
        $permission->update($data);

        return $permission;
    }

    /**
     * Deletes a permission by its unique identifier.
     *
     * @param  int  $id  Unique identifier of the permission to delete.
     * @return bool True if the permission was successfully deleted, false otherwise.
     */
    public function delete(int $id): bool
    {
        $permission = $this->find($id);

        return $permission ? $permission->delete() : false;
    }

    /**
     * Checks if a permission with the given name and guard already exists.
     *
     * Typically used in the **CreatePermission** use case to prevent duplicates.
     *
     * @param  string  $name  Permission name to check.
     * @param  string  $guard_name  Guard name associated with the permission.
     * @return bool True if a permission with the same name and guard exists, false otherwise.
     */
    public function exists(string $name, string $guard_name): bool
    {
        return $this->query()
            ->where('name', $name)
            ->where('guard_name', $guard_name)
            ->exists();
    }

    /**
     * Checks if a permission with the given name and guard already exists,
     * excluding a specific permission ID.
     *
     * Typically used in the **UpdatePermission** use case to ensure uniqueness
     * without conflicting with the current permission being updated.
     *
     * @param  string  $name  Permission name to check.
     * @param  string  $guard_name  Guard name associated with the permission.
     * @param  int  $exceptId  Permission ID to exclude from the check.
     * @return bool True if another permission with the same name and guard exists, false otherwise.
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
