<?php
# src/Application/Laboratory/DTOs/V1/CreateLaboratoryDTO.php

namespace App\Application\Laboratory\DTOs\V1;

final class CreateLaboratoryDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $country,
        public readonly bool $isActive = true,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            country: $data['country'],
            isActive: (bool)($data['is_active'] ?? true),
        );
    }
}