<?php
# src/Domain/PharmaceuticalForm/ValueObjects/PharmaceuticalFormId.php

namespace App\Domain\PharmaceuticalForm\ValueObjects;

final class PharmaceuticalFormId
{
    public function __construct(private int $value)
    {
        if ($value <= 0) {
            throw new \InvalidArgumentException('PharmaceuticalFormId must be positive.');
        }
    }

    public function value(): int
    {
        return $this->value;
    }
}