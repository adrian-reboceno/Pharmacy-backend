<?php

// app/Domain/User/ValueObjects/UserEmail.php

namespace App\Domain\User\ValueObjects;

use App\Domain\User\Exceptions\InvalidUserValueException;

/**
 * Value Object: UserEmail
 *
 * Represents and validates an immutable email address.
 * Ensures the email follows a valid RFC format.
 */
final class UserEmail
{
    private string $value;

    /**
     * @param  string  $value  The user's email address.
     *
     * @throws InvalidUserValueException if the email format is invalid.
     */
    public function __construct(string $value)
    {
        $value = strtolower(trim($value));

        if (! filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidUserValueException("Invalid email format: {$value}");
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(UserEmail $other): bool
    {
        return $this->value === $other->value();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
