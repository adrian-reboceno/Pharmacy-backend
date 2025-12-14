<?php

// app/Presentation/DTOs/V1/PermissionDTO.php

namespace App\Presentation\DTOs\V1;

use Spatie\Permission\Models\Permission;

/**
 * Data Transfer Object (DTO) for representing a Permission.
 *
 * This DTO encapsulates permission information and provides helper
 * methods to format the data for API responses.
 *
 * Applied principles:
 * - **SRP (Single Responsibility Principle):** Responsible only for carrying
 *   permission data to the presentation layer.
 */
class PermissionDTO
{
    /**
     * Permission unique identifier.
     */
    public int $id;

    /**
     * Permission name.
     */
    public string $name;

    /**
     * Guard name associated with the permission (e.g., 'api', 'web').
     */
    public string $guard_name;

    /**
     * PermissionDTO constructor.
     */
    public function __construct(int $id, string $name, string $guard_name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->guard_name = $guard_name;
    }

    /**
     * Create a PermissionDTO instance from a Permission model.
     */
    public static function fromModel(Permission $permission): self
    {
        return new self(
            $permission->id,
            $permission->name,
            $permission->guard_name
        );
    }

    /**
     * Convert the DTO into an array representation suitable for API responses.
     *
     * @return array<string,mixed> Associative array including permission attributes.
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'guard_name' => $this->guard_name,
        ];
    }
}
