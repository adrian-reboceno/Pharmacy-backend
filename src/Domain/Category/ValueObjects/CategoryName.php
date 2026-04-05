<?php
# src/Domain/Category/ValueObjects/CategoryName.php

namespace App\Domain\Category\ValueObjects;

final class CategoryName
{
    public function __construct(private string $value)
    {
        $value = trim($value);
        if ($value === '') {
            throw new \InvalidArgumentException('CategoryName cannot be empty.');
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
