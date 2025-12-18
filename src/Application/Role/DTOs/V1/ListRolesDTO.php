<?php
# src/Application/Role/DTOs/V1/ListRolesDTO.php

namespace App\Application\Role\DTOs\V1;

final class ListRolesDTO
{
    public function __construct(
        public readonly int $page = 1,
        public readonly int $perPage = 15,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            page: (int) ($data['page'] ?? 1),
            perPage: (int) ($data['per_page'] ?? 15),
        );
    }
}
