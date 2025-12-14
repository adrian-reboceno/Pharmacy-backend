<?php

// app/Domain/Permission/Repositories/PermissionRepositoryInterface.php

namespace App\Domain\Permission\Repositories;

use Illuminate\Database\Eloquent\Builder;

/**
 * Repository Interface for managing Permissions in the Domain layer.
 *
 * This interface defines the contract for interacting with the
 * persistence layer (database) regarding permission entities.
 *
 * Applied principles:
 * - **DIP (Dependency Inversion Principle):** Higher-level layers
 *   depend on this abstraction, not a concrete implementation.
 */
interface PermissionRepositoryInterface
{
    /**
     * Returns a query builder for permissions.
     *
     * This allows applying filters, sorting, and pagination.
     */
    public function query(): Builder;

    /**
     * Find a permission by its unique identifier.
     *
     * @param  int  $id  Permission ID.
     * @return \App\Domain\Permission\Permission|null Returns the permission entity or null if not found.
     */
    public function find(int $id): ?object;

    /**
     * Create a new permission.
     *
     * @param  array  $data  Data required to create a permission (e.g., name, guard_name).
     * @return \App\Domain\Permission\Permission The newly created permission entity.
     */
    public function create(array $data): object;

    /**
     * Update an existing permission.
     *
     * @param  int  $id  Unique identifier of the permission to update.
     * @param  array  $data  Data to update (e.g., name, guard_name).
     * @return \App\Domain\Permission\Permission The updated permission entity.
     */
    public function update(int $id, array $data): object;

    /**
     * Delete a permission by its unique identifier.
     *
     * @param  int  $id  Permission ID to delete.
     * @return bool True if the permission was successfully deleted, false otherwise.
     */
    public function delete(int $id): bool;
}
