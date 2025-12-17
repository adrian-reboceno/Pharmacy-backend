<?php
# src/Application/Permission/DTOs/V1/CreatePermissionDTO.php

namespace App\Application\Permission\DTOs\V1;

final class CreatePermissionDTO
{
    public function __construct(
        public string $name,
        public string $guardName = 'api',
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            guardName: $data['guard_name'] ?? 'api',
        );
    }
}
