<?php
# src/Domain/User/ValueObjects/UserRoles.php

namespace App\Domain\User\ValueObjects;

use App\Domain\User\Exceptions\InvalidUserValueException;

/**
 * Value Object: UserRoles
 *
 * Represents a set of roles assigned to a user.
 * Ensures all roles are valid, non-empty and unique.
 */
final class UserRoles
{
    /**
     * @var array<int,string> List of role names.
     */
    private array $roles;

    /**
     * @param array<int,string> $roles Initial list of roles.
     *
     * @throws InvalidUserValueException If any role value is invalid.
     */
    public function __construct(array $roles = [])
    {
        if (empty($roles)) {
            $this->roles = [];
            return;
        }

        foreach ($roles as $role) {
            if (! is_string($role) || $role === '') {
                throw new InvalidUserValueException('Invalid role value provided.');
            }
        }

        // Normalize: unique values and re-indexed array.
        $this->roles = array_values(array_unique($roles));
    }

    /**
     * Get all roles as a plain array of strings.
     *
     * @return array<int,string>
     */
    public function names(): array
    {
        return $this->roles;
    }

    /**
     * Add a role and return a new instance.
     *
     * @param string $role Role name to add.
     *
     * @throws InvalidUserValueException If the role value is invalid.
     */
    public function add(string $role): self
    {
        if ($role === '') {
            throw new InvalidUserValueException('Invalid role value provided.');
        }

        $roles = $this->roles;

        if (! in_array($role, $roles, true)) {
            $roles[] = $role;
        }

        return new self($roles);
    }

    /**
     * Remove a role and return a new instance.
     *
     * @param string $role Role name to remove.
     */
    public function remove(string $role): self
    {
        $roles = array_filter(
            $this->roles,
            fn (string $r): bool => $r !== $role
        );

        return new self(array_values($roles));
    }

    /**
     * Check whether the given role is present.
     *
     * @param string $role Role name to check.
     */
    public function contains(string $role): bool
    {
        return in_array($role, $this->roles, true);
    }

    /**
     * String representation: comma-separated list of roles.
     */
    public function __toString(): string
    {
        return implode(', ', $this->roles);
    }
}
