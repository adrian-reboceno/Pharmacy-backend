<?php

// app/Domain/User/Entities/User.php

namespace App\Domain\User\Entities;

use App\Domain\User\ValueObjects\UserEmail;
use App\Domain\User\ValueObjects\UserId;
use App\Domain\User\ValueObjects\UserName;
use App\Domain\User\ValueObjects\UserPassword;
use App\Domain\User\ValueObjects\UserRoles;

/**
 * Domain Entity: User
 *
 * Represents the **aggregate root** of the User domain. This class defines
 * the core business rules, behaviors, and invariants for system users,
 * completely decoupled from infrastructure or framework-specific details.
 *
 * ## Responsibilities
 * - Encapsulates user-related domain logic.
 * - Guarantees data integrity through **immutable Value Objects**.
 * - Acts as the **Aggregate Root**, controlling access to related entities
 *   (e.g., roles, permissions).
 *
 * Each property is defined as a Value Object to ensure validity and
 * consistency across the entity's lifecycle.
 */
final class User
{
    /**
     * Unique identifier of the user.
     */
    private ?UserId $id;

    /**
     * The user's full name.
     */
    private UserName $name;

    /**
     * The user's validated email address.
     */
    private UserEmail $email;

    /**
     * The hashed password encapsulated as a Value Object.
     */
    private UserPassword $password;

    /**
     * The collection of roles assigned to the user.
     */
    private UserRoles $roles;

    /**
     * Create a new immutable instance of the User entity.
     *
     * @param  UserId|null  $id  Unique identifier of the user.
     * @param  UserName  $name  The user's name.
     * @param  UserEmail  $email  The user's email address.
     * @param  UserPassword  $password  The user's hashed password.
     * @param  UserRoles  $roles  The roles assigned to the user.
     */
    public function __construct(
        ?UserId $id,
        UserName $name,
        UserEmail $email,
        UserPassword $password,
        UserRoles $roles
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->roles = $roles;
    }

    // ─────────────────────────────────────────────
    // Accessors (Getters)
    // ─────────────────────────────────────────────

    /**
     * Get the user's unique identifier.
     */
    public function id(): ?UserId
    {
        return $this->id;
    }

    /**
     * Get the user's name.
     */
    public function name(): UserName
    {
        return $this->name;
    }

    /**
     * Get the user's email.
     */
    public function email(): UserEmail
    {
        return $this->email;
    }

    /**
     * Get the user's password.
     */
    public function password(): UserPassword
    {
        return $this->password;
    }

    /**
     * Get all roles currently assigned to the user.
     */
    public function roles(): UserRoles
    {
        return $this->roles;
    }

    // ─────────────────────────────────────────────
    // Domain Behavior (Business Logic)
    // ─────────────────────────────────────────────

    /**
     * Change the user's name.
     *
     * @param  UserName  $newName  The new validated user name.
     */
    public function rename(UserName $newName): void
    {
        $this->name = $newName;
    }

    /**
     * Change the user's email.
     *
     * @param  UserEmail  $newEmail  The new validated email.
     */
    public function changeEmail(UserEmail $newEmail): void
    {
        $this->email = $newEmail;
    }

    /**
     * Change the user's password.
     *
     * The password must already be validated and hashed
     * according to domain rules.
     *
     * @param  UserPassword  $newPassword  The new validated password.
     */
    public function changePassword(UserPassword $newPassword): void
    {
        $this->password = $newPassword;
    }

    /**
     * Replace the entire set of user roles.
     *
     * @param  UserRoles  $roles  The new set of roles.
     */
    public function assignRoles(UserRoles $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * Add a new role to the user.
     *
     * Delegates validation and duplicate prevention to the
     * {@see UserRoles} Value Object.
     *
     * @param  string  $role  The role name to add.
     */
    public function addRole(string $role): void
    {
        $this->roles = $this->roles->add($role);
    }

    /**
     * Remove a role from the user.
     *
     * If the role does not exist, no change occurs.
     *
     * @param  string  $role  The role name to remove.
     */
    public function removeRole(string $role): void
    {
        $this->roles = $this->roles->remove($role);
    }

    /**
     * Check if the user has a specific role.
     *
     * @param  string  $role  The role name to verify.
     * @return bool True if the user has the specified role.
     */
    public function hasRole(string $role): bool
    {
        return $this->roles->contains($role);
    }

    // ─────────────────────────────────────────────
    // Infrastructure Mapping / Serialization
    // ─────────────────────────────────────────────

    /**
     * Convert the entity into an associative array.
     *
     * This is typically used for transferring data to the
     * infrastructure or application layer (e.g., DTOs, persistence).
     *
     * @return array{
     *     id: string|int|null,
     *     name: string,
     *     email: string,
     *     password: string,
     *     roles: string[]
     * }
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id?->value(),
            'name' => $this->name->value(),
            'email' => $this->email->value(),
            'password' => $this->password->value(),
            'roles' => $this->roles->names(),
        ];
    }
}
