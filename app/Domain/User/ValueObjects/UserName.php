<?php

// app/Domain/User/ValueObjects/UserName.php

namespace App\Domain\User\ValueObjects;

use App\Domain\User\Exceptions\InvalidUserValueException;

/**
 * Value Object: UserName
 *
 * Represents a validated, human-readable user name.
 * Enforces formatting rules and non-empty constraints.
 */
final class UserName
{
    private string $value;

    /**
     * @param  string  $value  The user's full name.
     *
     * @throws InvalidUserValueException if the name is invalid.
     */
    public function __construct(string $value)
    {
        $value = trim($value);

        if ($value === '') {
            throw new InvalidUserValueException('User name cannot be empty.');
        }

        if (strlen($value) < 2) {
            throw new InvalidUserValueException('User name must be at least 2 characters.');
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(UserName $other): bool
    {
        return strtolower($this->value) === strtolower($other->value());
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
