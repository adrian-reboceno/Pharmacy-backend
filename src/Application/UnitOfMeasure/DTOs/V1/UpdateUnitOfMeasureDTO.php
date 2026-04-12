<?php
# src/Application/UnitOfMeasure/DTOs/V1/UpdateUnitOfMeasureDTO.php

namespace Application\UnitOfMeasure\DTOs\V1;

final class UpdateUnitOfMeasureDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $symbol,
        public readonly bool $isActive = true,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int)$data['id'],
            name: $data['name'],
            symbol: $data['symbol'],
            isActive: (bool)($data['is_active'] ?? true),
        );
    }
}