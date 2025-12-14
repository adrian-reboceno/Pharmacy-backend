<?php

// src/Domain/User/ValueObjects/UserPassword.php

namespace App\Domain\User\ValueObjects;

use App\Domain\User\Exceptions\InvalidUserValueException;

/**
 * Value Object: UserPassword
 *
 * Encapsulates the user's password as a hash and centralizes
 * validation and verification logic at the domain level.
 *
 * This value object never exposes the plain-text password.
 */
final class UserPassword
{
    /**
     * Hashed password value.
     */
    private string $hash;

    /**
     * @param  string  $hash  Already hashed password value.
     *
     * @throws InvalidUserValueException If the hash is empty.
     */
    private function __construct(string $hash)
    {
        if ($hash === '') {
            throw new InvalidUserValueException('Password hash cannot be empty.');
        }

        $this->hash = $hash;
    }

    /**
     * Create a UserPassword from a plain-text password.
     *
     * This method validates the plain-text password against domain rules
     * (e.g. minimum length) and then hashes it using a secure algorithm.
     *
     * @param  string  $plain  Plain-text password.
     *
     * @throws InvalidUserValueException If the plain-text password is invalid.
     */
    public static function fromPlain(string $plain): self
    {
        $plain = trim($plain);

        if (mb_strlen($plain) < 8) {
            throw new InvalidUserValueException('Password must be at least 8 characters.');
        }

        return new self(password_hash($plain, PASSWORD_BCRYPT));
    }

    /**
     * Create a UserPassword from an existing hash.
     *
     * Useful when reconstructing the aggregate from persistence.
     *
     * @param  string  $hash  Hashed password value.
     */
    public static function fromHash(string $hash): self
    {
        return new self($hash);
    }

    /**
     * Get the underlying hashed password value.
     */
    public function value(): string
    {
        return $this->hash;
    }

    /**
     * Verify a plain-text password against the stored hash.
     *
     * @param  string  $plain  Plain-text password to verify.
     * @return bool True if the password matches the hash, false otherwise.
     */
    public function verify(string $plain): bool
    {
        return password_verify($plain, $this->hash);
    }
}
