<?php
# src/Application/PharmaceuticalForm/DTOs/V1/UpdatePharmaceuticalFormDTO.php

namespace App\Application\PharmaceuticalForm\DTOs\V1;

final class UpdatePharmaceuticalFormDTO
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $name = null,
        public readonly ?bool $isActive = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int)$data['id'],
            name: $data['name'] ?? null,
            isActive: array_key_exists('is_active', $data)
                ? (bool)$data['is_active']
                : null,
        );
    }
}