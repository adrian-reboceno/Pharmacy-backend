<?php
# src/Application/User/DTOs/V1/UpdateUserDTO.php

namespace App\Application\User\DTOs\V1;

/**
 * Data Transfer Object: UpdateUserDTO
 *
 * Represents the data used to update an existing User.
 *
 * Supports partial updates:
 * - The ID is required to identify the user.
 * - The rest of the fields are optional and only applied
 *   when not null.
 */
final class UpdateUserDTO
{
    /**
     * @param string|int   $id       User identifier.
     * @param string|null  $name     Optional updated name.
     * @param string|null  $email    Optional updated email.
     * @param string|null  $password Optional updated plain-text password
     *                               (it will be hashed in the domain layer).
     * @param string[]|null $roles   Optional updated roles (replaces the set).
     */
    public function __construct(
        public readonly string|int $id,
        public readonly ?string $name = null,
        public readonly ?string $email = null,
        public readonly ?string $password = null,
        public readonly ?array $roles = null
    ) {
    }

    /**
     * Create a new DTO instance from an associative array.
     *
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? '',
            name: $data['name'] ?? null,
            email: $data['email'] ?? null,
            password: $data['password'] ?? null,
            roles: $data['roles'] ?? null
        );
    }

    /**
     * Convert the DTO into a plain array representation.
     *
     * This method filters out null fields (except for ID) to avoid
     * overriding existing data unintentionally.
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return array_filter(
            [
                'id'       => $this->id,
                'name'     => $this->name,
                'email'    => $this->email,
                'password' => $this->password,
                'roles'    => $this->roles,
            ],
            static fn ($value, string $key) => $key === 'id' || $value !== null,
            ARRAY_FILTER_USE_BOTH
        );
    }
}
