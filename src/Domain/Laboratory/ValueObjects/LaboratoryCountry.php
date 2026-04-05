<?php
# src/Domain/Laboratory/ValueObjects/LaboratoryCountry.php

namespace App\Domain\Laboratory\ValueObjects;

final class LaboratoryCountry
{
    public function __construct(private string $value)
    {
        if (empty($value)) {
            throw new \InvalidArgumentException('LaboratoryCountry cannot be empty.');
        }
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