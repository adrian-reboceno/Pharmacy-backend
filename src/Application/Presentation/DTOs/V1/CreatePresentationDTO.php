<?php
# src/Application/Presentation/DTOs/V1/CreatePresentationDTO.php

namespace App\Application\Presentation\DTOs\V1;

final class CreatePresentationDTO
{
    public function __construct(
        public readonly string $name,
        public readonly bool $isActive = true,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
           name: $data['name'],
           isActive: (bool)($data['is_active'] ?? true),
        );
    }
}