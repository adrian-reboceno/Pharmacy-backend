<?php
# src/Application/PharmaceuticalForm/DTOs/V1/CreatePharmaceuticalFormDTO.php

namespace App\Application\PharmaceuticalForm\DTOs\V1;

final class CreatePharmaceuticalFormDTO
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