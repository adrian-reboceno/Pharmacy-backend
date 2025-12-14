<?php

// app/Application/Role/DTOs/V1/CreateRoleDTO.php

namespace App\Application\Role\DTOs\V1;

/**
 * Data Transfer Object (DTO) for creating a new role.
 *
 * This DTO encapsulates the data required to create a role
 * in the system, including the role's name, guard, and optional
 * permissions to be assigned.
 *
 * Applied principles:
 * - **SRP (Single Responsibility Principle):** Responsible only for
 *   carrying role creation data between layers.
 */
class CreateRoleDTO
{
    /**
     * The unique name of the role.
     *
     * Example: "admin", "editor", "viewer".
     */
    public string $name;

    /**
     * The guard name associated with the role.
     *
     * Guards separate authentication drivers in Laravel,
     * e.g. 'web', 'api'.
     *
     * Defaults to "api" if not provided.
     */
    public string $guard_name;

    /**
     * List of permission names to assign to the role.
     *
     * Example:
     * [
     *   "create-posts",
     *   "edit-posts",
     *   "delete-posts"
     * ]
     *
     * @var array<int,string>
     */
    public array $permissions;

    /**
     * Constructor.
     *
     * Initializes the DTO from an associative array of data.
     * If the guard_name is not provided, it defaults to 'api'.
     *
     * @param array{
     *   name: string,
     *   guard_name?: string,
     *   permissions?: array<int,string>
     * } $data Input array containing role creation data.
     */
    public function __construct(array $data)
    {
        $this->name = $data['name'] ?? '';
        $this->guard_name = $data['guard_name'] ?? 'api';
        $this->permissions = $data['permissions'] ?? [];
    }

    /**
     * Converts the DTO into an associative array.
     *
     * This is useful for passing the DTO data to repositories
     * or other layers that expect plain arrays.
     *
     * @return array{
     *   name: string,
     *   guard_name: string,
     *   permissions: array<int,string>
     * } Associative array with role attributes.
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
