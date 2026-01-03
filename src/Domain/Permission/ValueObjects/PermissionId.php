<?php
# src/Domain/Permission/ValueObjects/PermissionId.php

namespace App\Domain\Permission\ValueObjects;

final class PermissionId
{
    private int $value;

    public function __construct(int|string $value)
    {
        if (! is_numeric($value)) {
            throw new \InvalidArgumentException('PermissionId must be numeric.');
        }

        $intValue = (int) $value;

        if ($intValue <= 0) {
            throw new \InvalidArgumentException('PermissionId must be positive.');
        }

        $this->value = $intValue;
    }

    public function value(): int
    {
        return $this->value;
    }
}