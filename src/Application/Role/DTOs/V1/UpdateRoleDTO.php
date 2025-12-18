<?php
# src/Application/Role/DTOs/V1/UpdateRoleDTO.php

namespace App\Application\Role\DTOs\V1;

/**
 * DTO: UpdateRoleDTO
 */
final class UpdateRoleDTO
{
    /**
     * @param int         $id
     * @param string|null $name
     * @param string|null $guardName
     * @param string[]|null $permissions
     */
    public function __construct(
        public readonly int $id,
        public readonly ?string $name = null,
        public readonly ?string $guardName = null,
        public readonly ?array $permissions = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['id'],
            name: $data['name'] ?? null,
            guardName: $data['guard_name'] ?? null,
            permissions: $data['permissions'] ?? null,
        );
    }
}
