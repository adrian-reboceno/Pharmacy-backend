<?php

// src/Domain/User/ValueObjects/UserName.php

namespace App\Domain\User\ValueObjects;

use App\Domain\User\Exceptions\InvalidUserValueException;

/**
 * Value Object: UserName
 *
 * Represents a validated, human-readable user name.
 * Enforces non-empty and minimum-length constraints.
 */
final class UserName
{
    /**
     * Normalized user name value.
     */
    private string $value;

    /**
     * @param  string  $value  The user's full name.
     *
     * @throws InvalidUserValueException If the name is empty or does not meet
     *                                   the minimum length requirements.
     */
    public function __construct(string $value)
    {
        $value = trim($value);

        if ($value === '') {
            throw new InvalidUserValueException('User name cannot be empty.');
        }

        if (mb_strlen($value) < 2) {
            throw new InvalidUserValueException('User name must be at least 2 characters.');
        }

        $this->value = $value;
    }

    /**
     * Get the normalized name value.
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * Compare this name with another one for equality (case-insensitive).
     */
    public function equals(UserName $other): bool
    {
        return strtolower($this->value) === strtolower($other->value());
    }

    /**
     * String representation of the user name.
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
