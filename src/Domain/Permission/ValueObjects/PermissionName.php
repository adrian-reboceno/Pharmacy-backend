<?php
# src/Domain/Permission/ValueObjects/PermissionName.php

namespace App\Domain\Permission\ValueObjects;

final class PermissionName
{
    public function __construct(
        private string $value
    ) {
        $trimmed = trim($value);

        if ($trimmed === '') {
            throw new \InvalidArgumentException('Permission name cannot be empty.');
        }

        $this->value = $trimmed;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(PermissionName $other): bool
    {
        return $this->value === $other->value();
    }
}
