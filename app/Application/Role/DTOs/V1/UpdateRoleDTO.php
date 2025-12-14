<?php

// app/Application/Role/DTOs/V1/UpdateRoleDTO.php

namespace App\Application\Role\DTOs\V1;

/**
 * Data Transfer Object for updating an existing role.
 *
 * This DTO encapsulates the data required to update a role's permissions
 * and optionally the guard name. **The role's name should not be changed**
 * in the system to ensure consistency and avoid breaking references.
 *
 * Applied principles:
 * - **SRP (Single Responsibility Principle):** Responsible only for carrying
 *   role update data between layers.
 */
class UpdateRoleDTO
{
    /**
     * The role name (immutable after creation).
     */
    public string $name;

    /**
     * The guard name associated with the role (e.g., 'api', 'web').
     */
    public string $guard_name;

    /**
     * List of permission names to assign to the role.
     */
    public array $permissions;

    /**
     * Constructor.
     *
     * Initializes the DTO from an associative array of data.
     * The role name is included for reference but should not be updated.
     *
     * @param  array  $data  Associative array with keys 'name', 'guard_name', and 'permissions'.
     */
    public function __construct(array $data)
    {
        $this->name = $data['name'] ?? '';
        $this->guard_name = $data['guard_name'] ?? 'api';
        $this->permissions = $data['permissions'] ?? [];
    }

    /**
     * Converts the DTO to an associative array.
     *
     * Useful for passing the DTO data to repositories or other layers.
     *
     * @return array Associative array with keys 'name', 'guard_name', and 'permissions'.
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'guard_name' => $this->guard_name,
            'permissions' => $this->permissions,
        ];
    }
}
