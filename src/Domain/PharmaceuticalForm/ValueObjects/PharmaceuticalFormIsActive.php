<?php
# src/Domain/PharmaceuticalForm/ValueObjects/PharmaceuticalFormIsActive.php

namespace App\Domain\PharmaceuticalForm\ValueObjects;

final class PharmaceuticalFormIsActive
{
    public function __construct(private bool $value)
    {
    }

    public function value(): bool
    {
        return $this->value;
    }
}