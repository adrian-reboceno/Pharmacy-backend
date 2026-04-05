<?php
# src/Domain/Category/ValueObjects/CategoryIsActive.php

namespace App\Domain\Category\ValueObjects;

final class CategoryIsActive
{
    public function __construct(private bool $value)
    {
    }

    public function value(): bool
    {
        return $this->value;
    }
}
