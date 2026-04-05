<?php
# src/Domain/Laboratory/ValueObjects/LaboratoryIsActive.php

namespace App\Domain\Laboratory\ValueObjects;

final class LaboratoryIsActive
{
   public function __construct(private bool $value)
    {
    }

    public function value(): bool
    {
        return $this->value;
    }
}