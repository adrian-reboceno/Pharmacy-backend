<?php
# src/Domain/User/ValueObjects/UserEmail.php

namespace App\Domain\User\ValueObjects;

use App\Domain\User\Exceptions\InvalidUserValueException;

/**
 * Value Object: UserEmail
 *
 * Represents and validates an immutable email address.
 * Ensures the email is non-empty and follows a valid format.
 */
final class UserEmail
{
    /**
     * Normalized email address (lowercased, trimmed).
     */
    private string $value;

    /**
     * @param string $value The user's email address.
     *
     * @throws InvalidUserValueException If the email is empty or has an invalid format.
     */
    public function __construct(string $value)
    {
        $value = strtolower(trim($value));

        if ($value === '') {
            throw new InvalidUserValueException('User email cannot be empty.');
        }

        if (! filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidUserValueException("Invalid email format: {$value}");
        }

        $this->value = $value;
    }

    /**
     * Get the normalized email value.
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * Compare this email with another one for equality.
     */
    public function equals(UserEmail $other): bool
    {
        return $this->value === $other->value();
    }

    /**
     * String representation of the email.
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
