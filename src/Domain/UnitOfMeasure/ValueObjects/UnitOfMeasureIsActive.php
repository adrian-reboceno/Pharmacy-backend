<?php
# src/Domain/UnitOfMeasure/ValueObjects/UnitOfMeasureIsActive.php

namespace Domain\UnitOfMeasure\ValueObjects;

final class UnitOfMeasureIsActive
{
    public function __construct(private bool $value)
    {
    }

    public function value(): bool
    {
        return $this->value;
    }
}