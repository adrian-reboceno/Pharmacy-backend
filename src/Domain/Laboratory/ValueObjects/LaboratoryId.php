<?php
# src/Domain/Laboratory/ValueObjects/LaboratoryId.php

namespace App\Domain\Laboratory\ValueObjects;

final class LaboratoryId
{
    public function __construct(private int $value)
    {
        if ($value <= 0) {
            throw new \InvalidArgumentException('LaboratoryId must be positive.');
        }
    }

    public function value(): int
    {
        return $this->value;
    }
}
