<?php
# src/Application/Presentation/DTOs/V1/UpdatePresentationDTO.php

namespace App\Application\Presentation\DTOs\V1;

final class UpdatePresentationDTO
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
