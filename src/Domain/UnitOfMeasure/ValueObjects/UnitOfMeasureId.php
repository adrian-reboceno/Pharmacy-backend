<?php
# src/Domain/UnitOfMeasure/ValueObjects/UnitOfMeasureId.php

namespace App\Domain\UnitOfMeasure\ValueObjects;

final class UnitOfMeasureId
{
    public function __construct(private int $value)
    {
        if ($value <= 0) {
            throw new \InvalidArgumentException('UnitOfMeasureId must be positive.');
        }
    }

    public function value(): int
    {
        return $this->value;
    }
}


