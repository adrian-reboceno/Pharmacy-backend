<?php
# app/Application/User/DTOs/V1/CreateUserDTO.php

namespace App\Application\User\DTOs\V1;

/**
 * Data Transfer Object: CreateUserDTO
 *
 * Represents the input data required to create a new User.
 * 
 * This DTO isolates the application layer from raw request data,
 * providing a typed and validated structure that can be passed
 * safely into a UseCase or Domain Entity.
 *
 * @package App\Application\User\DTOs\V1
 */
final class CreateUserDTO
{
    /**
     * @param string $name      User's full name
     * @param string $email     User's email address
     * @param string $password  User's raw password (hashed in the repository or entity)
     * @param string[] $roles   Array of assigned role names
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
     * @param array $data
     * @return self
     *
     * @example
     * $dto = CreateUserDTO::fromArray([
     *     'name' => 'Jane Doe',
     *     'email' => 'jane@example.com',
     *     'password' => 'secret123',
     *     'roles' => ['admin']
     * ]);
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
     * This is particularly useful for passing data to repositories
     * or serializing into responses/logs.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => $this->password,
            'roles'    => $this->roles,
        ];
    }
}
