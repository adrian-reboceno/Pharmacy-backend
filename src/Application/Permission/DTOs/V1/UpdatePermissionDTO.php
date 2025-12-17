<?php
# src/Application/Permission/DTOs/V1/UpdatePermissionDTO.php

namespace App\Application\Permission\DTOs\V1;

final class UpdatePermissionDTO
{
    public function __construct(
        public int $id,
        public ?string $name = null,
        public ?string $guardName = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int)$data['id'],
            name: $data['name'] ?? null,
            guardName: $data['guard_name'] ?? null,
        );
    }
}
