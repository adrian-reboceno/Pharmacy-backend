<?php
# src/Domain/UnitOfMeasure/ValueObjects/UnitOfMeasureSymbol.php

namespace App\Domain\UnitOfMeasure\ValueObjects;

final class UnitOfMeasureSymbol
{
    public function __construct(private string $value)
    {
        $value = trim($value);
        if ($value === '') {
            throw new \InvalidArgumentException('UnitOfMeasureSymbol cannot be empty.');
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}