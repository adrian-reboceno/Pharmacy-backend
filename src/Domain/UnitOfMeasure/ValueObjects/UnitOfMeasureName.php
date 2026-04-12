<?php  
# src/Domain/UnitOfMeasure/ValueObjects/UnitOfMeasureName.php

namespace Domain\UnitOfMeasure\ValueObjects;

final class UnitOfMeasureName
{
    public function __construct(private string $value)
    {
        $value = trim($value);
        if ($value === '') {
            throw new \InvalidArgumentException('UnitOfMeasureName cannot be empty.');
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