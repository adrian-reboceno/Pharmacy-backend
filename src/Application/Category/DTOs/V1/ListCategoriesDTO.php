<?php
# src/Application/Category/DTOs/V1/ListCategoriesDTO.php

namespace App\Application\Category\DTOs\V1;

final class ListCategoriesDTO
{
    public function __construct(
        public readonly int $page = 1,
        public readonly int $perPage = 15,
        public readonly ?string $name = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            page: (int)($data['page'] ?? 1),
            perPage: (int)($data['per_page'] ?? 15),
            name: $data['name'] ?? null,
        );
    }
}
