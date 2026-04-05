<?php
# src/Domain/Category/ValueObjects/CategoryId.php

namespace App\Domain\Category\ValueObjects;

final class CategoryId
{
    public function __construct(private int $value)
    {
        if ($value <= 0) {
            throw new \InvalidArgumentException('CategoryId must be positive.');
        }
    }

    public function value(): int
    {
        return $this->value;
    }
}
