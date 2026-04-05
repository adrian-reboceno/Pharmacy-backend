<?php
# src/Domain/Laboratory/ValueObjects/LaboratoryName.php

namespace App\Domain\Laboratory\ValueObjects;

final class LaboratoryName
{
    public function __construct(private string $value)
    {
        if (empty($value)) {
            throw new \InvalidArgumentException('LaboratoryName cannot be empty.');
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