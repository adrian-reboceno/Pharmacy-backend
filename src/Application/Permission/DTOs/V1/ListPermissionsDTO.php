<?php
# src/Application/Permission/DTOs/V1/ListPermissionsDTO.php

namespace App\Application\Permission\DTOs\V1;

final class ListPermissionsDTO
{
    public function __construct(
        public int $page,
        public int $perPage,
        public readonly ?string $name = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            page:    (int) ($data['page'] ?? 1),
            perPage: (int) ($data['per_page'] ?? 10),
            name: $data['name'] ?? null,
        );
    }
}
