<?php

// app/Domain/User/ValueObjects/UserId.php

namespace App\Domain\User\ValueObjects;

use App\Domain\User\Exceptions\InvalidUserValueException;
use Ramsey\Uuid\Uuid;

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
    private string $value;

    /**
     * @param  string|int  $value  The unique identifier value.
     *
     * @throws InvalidUserValueException if the provided value is empty or invalid.
     */
    public function __construct(string|int $value)
    {
        $value = (string) $value;

        if (empty($value)) {
            throw new InvalidUserValueException('User ID cannot be empty.');
        }

        if (! Uuid::isValid($value) && ! ctype_digit($value)) {
            throw new InvalidUserValueException("Invalid User ID format: {$value}");
        }

        $this->value = $value;
    }

    /**
     * Returns the raw ID value.
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

    public function __toString(): string
    {
        return $this->value;
    }
}
