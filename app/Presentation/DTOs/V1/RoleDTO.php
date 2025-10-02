<?php
# app/Presentation/DTOs/V1/RoleDTO.php

namespace App\Presentation\DTOs\V1;

use Spatie\Permission\Models\Role;
use Illuminate\Support\Collection;

/**
 * Data Transfer Object (DTO) for representing a Role entity.
 *
 * This DTO encapsulates role information such as ID, name,
 * guard, and permissions. It also provides utility methods
 * to format data into a consistent structure suitable for API responses.
 *
 * Applied principles:
 * - **SRP (Single Responsibility Principle):** Responsible only for
 *   transferring role data to the presentation layer.
 */
class RoleDTO
{
    /**
     * Unique identifier of the role.
     *
     * @var int
     */
    public int $id;

    /**
     * Role name.
     *
     * @var string
     */
    public string $name;

    /**
     * Guard name associated with the role (e.g., 'api', 'web').
     *
     * @var string
     */
    public string $guard_name;

    /**
     * Role permissions.
     *
     * Possible types:
     * - null
     * - string (JSON encoded list of permissions)
     * - Collection of Permission models
     *
     * @var string|Collection|null
     */
    public $permissions;

    /**
     * RoleDTO constructor.
     *
     * @param int                    $id          Unique identifier of the role.
     * @param string                 $name        Name of the role.
     * @param string                 $guard_name  Guard name associated with the role.
     * @param string|Collection|null $permissions Role permissions (optional).
     */
    public function __construct(int $id, string $name, string $guard_name, $permissions = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->guard_name = $guard_name;
        $this->permissions = $permissions;
    }

    /**
     * Create a RoleDTO instance from a Role model.
     *
     * @param Role $role Role model instance.
     * @return self RoleDTO instance containing role data.
     */
    public static function fromModel(Role $role): self
    {
        return new self(
            $role->id,
            $role->name,
            $role->guard_name,
            $role->permissions,
        );
    }

    /**
     * Convert the DTO into an array representation for API responses.
     *
     * @return array<string,mixed> Associative array with role attributes and permissions.
     */
    public function toArray(): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'guard_name'  => $this->guard_name,
            'permissions' => $this->formatPermissions(),
        ];
    }

    /**
     * Format the permissions field into a structured array.
     *
     * Handles different input types:
     * - JSON string (decodes into array)
     * - Eloquent Collection (maps Permission models)
     * - Array of decoded permissions (returns directly)
     *
     * @return array<int,array<string,mixed>> Structured array of permissions.
     */
    private function formatPermissions(): array
    {
        return collect(
            is_string($this->permissions)
                ? json_decode($this->permissions, true)
                : $this->permissions
        )
        ->map(fn($permission) => is_array($permission) ? $permission : [
            'id'         => $permission->id,
            'name'       => $permission->name,
            'guard_name' => $permission->guard_name,
            'pivot'      => $permission->pivot ?? null,
        ])
        ->toArray();
    }
}
