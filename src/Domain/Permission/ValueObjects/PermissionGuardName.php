<?php
# src/Domain/Permission/ValueObjects/PermissionGuardName.php

namespace App\Domain\Permission\ValueObjects;

final class PermissionGuardName
{
    public function __construct(
        private string $value
    ) {
        $trimmed = trim($value);

        if ($trimmed === '') {
            throw new \InvalidArgumentException('Guard name cannot be empty.');
        }

        $this->value = $trimmed;
    }

    public function value(): string
    {
        return $this->value;
    }
}
