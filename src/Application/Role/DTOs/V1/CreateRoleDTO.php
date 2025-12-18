<?php
# src/Application/Role/DTOs/V1/CreateRoleDTO.php

namespace App\Application\Role\DTOs\V1;

/**
 * DTO: CreateRoleDTO
 */
final class CreateRoleDTO
{
    /**
     * @param string   $name
     * @param string   $guardName
     * @param string[] $permissions
     */
    public function __construct(
        public readonly string $name,
        public readonly string $guardName = 'api',
        public readonly array $permissions = [],
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            guardName: $data['guard_name'] ?? 'api',
            permissions: $data['permissions'] ?? [],
        );
    }
}
