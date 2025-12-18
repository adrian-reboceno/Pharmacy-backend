<?php
# src/Domain/Role/ValueObjects/RoleGuardName.php

namespace App\Domain\Role\ValueObjects;

/**
 * Value Object: RoleGuardName
 *
 * Normalmente 'web' o 'api' (para Spatie).
 */
final class RoleGuardName
{
    public function __construct(
        private string $value = 'api'
    ) {
        $trimmed = trim($this->value);

        if ($trimmed === '') {
            throw new \InvalidArgumentException('RoleGuardName cannot be empty.');
        }

        $this->value = $trimmed;
    }

    public function value(): string
    {
        return $this->value;
    }
}
