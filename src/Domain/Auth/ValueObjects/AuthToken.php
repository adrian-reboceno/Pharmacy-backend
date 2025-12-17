<?php
# src/Domain/Auth/ValueObjects/AuthToken.php

namespace App\Domain\Auth\ValueObjects;

use InvalidArgumentException;

/**
 * Value Object: AuthToken
 *
 * Represents an authentication token (e.g. JWT) as an immutable value object.
 */
final class AuthToken
{
    private string $value;

    /**
     * @param string $value Raw token string (e.g., JWT).
     */
    public function __construct(string $value)
    {
        $value = trim($value);

        if ($value === '') {
            throw new InvalidArgumentException('Auth token cannot be empty.');
        }

        $this->value = $value;
    }

    /**
     * Get the raw token string.
     */
    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
