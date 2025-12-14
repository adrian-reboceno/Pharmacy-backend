<?php
# src/Domain/User/ValueObjects/UserId.php

namespace App\Domain\User\ValueObjects;

use App\Domain\User\Exceptions\InvalidUserValueException;

/**
 * Value Object: UserId
 *
 * Represents the unique identifier for a User within the domain.
 *
 * The identifier may be a UUID or an integer, depending on how
 * the infrastructure persists entities, but at the domain level
 * it behaves as an immutable value object.
 */
final class UserId
{
    /**
     * Raw identifier value (numeric ID or UUID string).
     */
    private string $value;

    /**
     * @param string|int $value The unique identifier value.
     *
     * @throws InvalidUserValueException If the provided value is empty or invalid.
     */
    public function __construct(string|int $value)
    {
        $value = (string) $value;

        if ($value === '') {
            throw new InvalidUserValueException('User ID cannot be empty.');
        }

        // Accepted formats:
        //  - Numeric IDs (auto-increment)
        //  - UUIDs in a standard 36-character format
        $isNumeric = ctype_digit($value);
        $looksLikeUuid = (bool) preg_match(
            '/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/',
            $value
        );

        if (! $isNumeric && ! $looksLikeUuid) {
            throw new InvalidUserValueException("Invalid User ID format: {$value}");
        }

        $this->value = $value;
    }

    /**
     * Get the raw ID value.
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * Compare equality with another UserId.
     */
    public function equals(UserId $other): bool
    {
        return $this->value === $other->value();
    }

    /**
     * String representation of the identifier.
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
