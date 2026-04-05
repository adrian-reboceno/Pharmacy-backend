<?php
# src/Domain/Category/ValueObjects/CategoryDescription.php

namespace App\Domain\Category\ValueObjects;

final class CategoryDescription
{
    public function __construct(private string $value)
    {
        $this->value = trim($value);
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
