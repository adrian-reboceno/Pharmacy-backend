<?php
# src/Application/Category/DTOs/V1/UpdateCategoryDTO.php

namespace App\Application\Category\DTOs\V1;

final class UpdateCategoryDTO
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $name = null,
        public readonly ?string $description = null,
        public readonly ?bool $isActive = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int)$data['id'],
            name: $data['name'] ?? null,
            description: $data['description'] ?? null,
            isActive: array_key_exists('is_active', $data)
                ? (bool)$data['is_active']
                : null,
        );
    }
}
