<?php
# app/Domain/Role/Repositories/RoleRepositoryInterface.php

namespace App\Domain\Role\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Role Repository Interface.
 *
 * This interface defines the contract for role persistence operations.
 * It ensures that the application layer depends on abstractions rather
 * than concrete implementations, following the **Dependency Inversion Principle (DIP)**.
 *
 * Applied principles:
 * - **SRP (Single Responsibility Principle):** Defines only the persistence operations for roles.
 * - **DIP (Dependency Inversion Principle):** Higher-level modules (use cases)
 *   depend on this interface instead of a specific implementation (e.g., Eloquent).
 */
interface RoleRepositoryInterface
{
    /**
     * Returns a query builder for roles.
     *
     * This allows applying filters, sorting, and pagination in higher layers
     * without exposing the underlying implementation details.
     *
     * @return Builder Query builder instance for roles.
     */
    public function query(): Builder;

    /**
     * Finds a role by its unique identifier.
     *
     * @param int $id Unique identifier of the role.
     * @return object|null Role instance if found, null otherwise.
     */
    public function find(int $id): ?object;

    /**
     * Creates a new role.
     *
     * @param array $data Associative array containing role attributes.
     * @return object The newly created role instance.
     */
    public function create(array $data): object;

    /**
     * Updates an existing role.
     *
     * @param int $id Unique identifier of the role to update.
     * @param array $data Associative array of updated role attributes.
     * @return object The updated role instance.
     */
    public function update(int $id, array $data): object;

    /**
     * Deletes a role by its unique identifier.
     *
     * @param int $id Unique identifier of the role to delete.
     * @return bool True if the role was successfully deleted, false otherwise.
     */
    public function delete(int $id): bool;
}
