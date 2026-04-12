<?php
# src/Application/UnitOfMeasure/DTOs/V1/CreateUnitOfMeasureDTO.php

namespace Application\UnitOfMeasure\DTOs\V1;

final class CreateUnitOfMeasureDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $symbol,
        public readonly bool $isActive = true,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            symbol: $data['symbol'],
            isActive: (bool)($data['is_active'] ?? true),
        );
    }
}