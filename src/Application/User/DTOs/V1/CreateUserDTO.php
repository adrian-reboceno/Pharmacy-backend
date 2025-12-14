<?php

// src/Application/User/DTOs/V1/CreateUserDTO.php

namespace App\Application\User\DTOs\V1;

/**
 * Data Transfer Object: CreateUserDTO
 *
 * Represents the input data required to create a new User.
 *
 * This DTO isolates the Application layer from raw request data,
 * providing a typed structure that can be safely passed into
 * a Use Case.
 */
final class CreateUserDTO
{
    /**
     * @param  string  $name  User's full name.
     * @param  string  $email  User's email address.
     * @param  string  $password  User's plain-text password
     *                            (it will be hashed in the domain layer).
     * @param  string[]  $roles  Array of assigned role names.
     */
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly array $roles = []
    ) {}

    /**
     * Create a new DTO instance from an associative array.
     *
     * @param  array<string,mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? '',
            email: $data['email'] ?? '',
            password: $data['password'] ?? '',
            roles: $data['roles'] ?? []
        );
    }

    /**
     * Convert the DTO into a plain array representation.
     *
     * Useful for logging, debugging or passing data
     * across layers that expect arrays.
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'roles' => $this->roles,
        ];
    }
}
