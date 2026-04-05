<?php
# src/Application/Laboratory/DTOs/V1/UpdateLaboratoryDTO.php

namespace App\Application\Laboratory\DTOs\V1;

final class UpdateLaboratoryDTO
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $name = null,
        public readonly ?string $country = null,
        public readonly ?bool $isActive = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int)$data['id'],
            name: $data['name'] ?? null,
            country: $data['country'] ?? null,
           isActive: array_key_exists('is_active', $data)
                ? (bool)$data['is_active']
                : null,
        );
    }
}   