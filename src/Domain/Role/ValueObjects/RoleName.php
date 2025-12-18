<?php
# src/Domain/Role/ValueObjects/RoleName.php

namespace App\Domain\Role\ValueObjects;

/**
 * Value Object: RoleName
 */
final class RoleName
{
    public function __construct(
        private string $value
    ) {
        $trimmed = trim($this->value);

        if ($trimmed === '') {
            throw new \InvalidArgumentException('RoleName cannot be empty.');
        }

        $this->value = $trimmed;
    }

    public function value(): string
    {
        return $this->value;
    }
}
