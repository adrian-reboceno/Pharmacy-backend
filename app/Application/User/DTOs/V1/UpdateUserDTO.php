<?php

// app/Application/User/DTOs/V1/UpdateUserDTO.php

namespace App\Application\User\DTOs\V1;

/**
 * Data Transfer Object: UpdateUserDTO
 *
 * Represents the data used to update an existing User.
 *
 * This DTO supports partial updates — fields can be null
 * if they are not meant to be changed.
 */
final class UpdateUserDTO
{
    /**
     * @param  string|null  $name  Optional updated name
     * @param  string|null  $email  Optional updated email
     * @param  string|null  $password  Optional updated password (hashed later)
     * @param  string[]|null  $roles  Optional updated roles
     */
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $email = null,
        public readonly ?string $password = null,
        public readonly ?array $roles = null
    ) {}

    /**
     * Create a new DTO instance from an associative array.
     *
     *
     * @example
     * $dto = UpdateUserDTO::fromArray([
     *     'email' => 'newmail@example.com',
     *     'roles' => ['editor']
     * ]);
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            email: $data['email'] ?? null,
            password: $data['password'] ?? null,
            roles: $data['roles'] ?? null
        );
    }

    /**
     * Convert the DTO into a plain array representation.
     *
     * This method filters out null fields to avoid overriding
     * existing data unnecessarily.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'roles' => $this->roles,
        ], static fn ($value) => $value !== null);
    }
}
