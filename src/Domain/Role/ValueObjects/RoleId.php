<?php
# src/Domain/Role/ValueObjects/RoleId.php

namespace App\Domain\Role\ValueObjects;

/**
 * Value Object: RoleId
 */
final class RoleId
{
    public function __construct(
        private int $value
    ) {
        if ($this->value <= 0) {
            throw new \InvalidArgumentException('RoleId must be positive.');
        }
    }

    public function value(): int
    {
        return $this->value;
    }
}
