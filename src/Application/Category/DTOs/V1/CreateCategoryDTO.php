<?php
# src/Application/Category/DTOs/V1/CreateCategoryDTO.php

namespace App\Application\Category\DTOs\V1;

final class CreateCategoryDTO
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $description = null,
        public readonly bool $isActive = true,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            description: $data['description'] ?? null,
            isActive: (bool)($data['is_active'] ?? true),
        );
    }
}
