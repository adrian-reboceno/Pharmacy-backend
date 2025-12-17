<?php
# src/Domain/Permission/ValueObjects/PermissionId.php

namespace App\Domain\Permission\ValueObjects;

final class PermissionId
{
    public function __construct(
        private int $value
    ) {
        if ($value <= 0) {
            throw new \InvalidArgumentException('PermissionId must be positive.');
        }
    }

    public function value(): int
    {
        return $this->value;
    }
}
