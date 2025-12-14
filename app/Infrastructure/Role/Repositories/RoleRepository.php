<?php

// app/Infrastructure/Role/Repositories/RoleRepository.php

namespace App\Infrastructure\Role\Repositories;

use App\Domain\Role\Repositories\RoleRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Eloquent implementation of the RoleRepositoryInterface.
 *
 * This repository provides persistence operations for roles using
 * the Spatie\Permission `Role` model. It acts as the bridge between
 * the domain layer and the database (infrastructure layer), ensuring
 * that higher-level modules depend only on abstractions.
 *
 * Applied principles:
 * - **SRP (Single Responsibility Principle):** Handles only persistence operations for roles.
 * - **DIP (Dependency Inversion Principle):** Implements the domain-defined
 *   `RoleRepositoryInterface`, allowing the application and domain layers
 *   to remain decoupled from the database.
 */
class RoleRepository implements RoleRepositoryInterface
{
    /**
     * Get a query builder for roles.
     *
     * @return Builder Query builder instance for roles.
     */
    public function query(): Builder
    {
        return Role::query();
    }

    /**
     * Find a role by its unique identifier.
     *
     * @param  int  $id  Role unique identifier.
     * @return Role|null The Role instance if found, null otherwise.
     */
    public function find(int $id): ?object
    {
        return Role::find($id);
    }

    /**
     * Create a new role.
     *
     * Also synchronizes permissions if they are provided.
     *
     * @param  array  $data  Associative array containing role attributes:
     *                       - name: string (required)
     *                       - guard_name: string (optional, defaults to 'api')
     *                       - permissions: array<string> (optional)
     * @return Role The newly created role instance.
     */
    public function create(array $data): object
    {
        $role = Role::create($data);

        if (! empty($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        return $role;
    }

    /**
     * Update an existing role by its ID.
     *
     * ⚠️ This method **does not allow updating the role name**.
     * It only updates allowed attributes such as guard_name
     * and synchronizes permissions if provided.
     *
     * @param  int  $id  Role unique identifier.
     * @param  array  $data  Associative array of role attributes to update:
     *                       - guard_name: string (optional)
     *                       - permissions: array<string> (optional)
     * @return Role The updated role instance.
     */
    public function update(int $id, array $data): object
    {
        $role = $this->find($id);

        if (! empty($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        return $role;
    }

    /**
     * Delete a role by its unique identifier.
     *
     * @param  int  $id  Role unique identifier.
     * @return bool True if the role was successfully deleted, false otherwise.
     */
    public function delete(int $id): bool
    {
        $role = $this->find($id);

        return $role ? $role->delete() : false;
    }

    /**
     * Check if a role with the given name and guard already exists.
     *
     * Typically used in the **CreateRole** use case to prevent duplicates.
     *
     * @param  string  $name  Role name to check.
     * @param  string  $guard_name  Guard name associated with the role.
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
     * Check if a role with the given name and guard already exists,
     * excluding a specific role ID.
     *
     * Typically used in the **UpdateRole** use case to enforce uniqueness
     * without conflicting with the role being updated.
     *
     * @param  string  $name  Role name to check.
     * @param  string  $guard_name  Guard name associated with the role.
     * @param  int  $exceptId  Role ID to exclude from the check.
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

    /**
     * Validate a list of permission names against the database.
     *
     * This method separates permissions into valid and invalid sets.
     * It does not throw exceptions; the responsibility of handling invalid
     * permissions belongs to the application use case (e.g., **UpdateRole**).
     *
     * @param  array<string>  $permissions  Array of permission names to validate.
     * @return array{valid: array<string>, invalid: array<string>} Arrays containing valid and invalid permission names.
     */
    public function validatePermissions(array $permissions): array
    {
        $validPermissions = Permission::whereIn('name', $permissions)
            ->pluck('name')
            ->toArray();

        $invalidPermissions = array_diff($permissions, $validPermissions);

        return [
            'valid' => $validPermissions,
            'invalid' => $invalidPermissions,
        ];
    }
}
