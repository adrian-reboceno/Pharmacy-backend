<?php
# app/Presentation/DTOs/V1/User/UserResponseDTO.php

namespace App\Presentation\DTOs\V1\User;

use App\Domain\User\Entities\User as DomainUser;

/**
 * UserResponseDTO
 *
 * Data Transfer Object (DTO) representing a single user in API responses.
 * This class is used to transform a domain User entity into a structure
 * suitable for presentation, ensuring that only relevant fields are exposed.
 *
 * Responsibilities:
 *  - Encapsulate user data for API responses.
 *  - Transform a Domain User entity to a response-friendly array.
 *  - Provide a simple interface for serialization.
 */
final class UserResponseDTO
{
    /**
     * The unique identifier of the user.
     *
     * Using string to support both numeric IDs and UUIDs.
     */
    public string $id;

    /**
     * The user's full name.
     */
    public string $name;

    /**
     * The user's email address.
     */
    public string $email;

    /**
     * List of role names assigned to the user.
     *
     * @var string[]
     */
    public array $roles;

    /**
     * Constructor.
     *
     * @param string   $id    User identifier
     * @param string   $name  User full name
     * @param string   $email User email address
     * @param string[] $roles List of role names
     */
    public function __construct(string $id, string $name, string $email, array $roles)
    {
        $this->id    = $id;
        $this->name  = $name;
        $this->email = $email;
        $this->roles = $roles;
    }

    /**
     * Create a UserResponseDTO from a domain User entity.
     */
    public static function fromEntity(DomainUser $user): self
    {
        return new self(
            id: (string) $user->id()->value(),
            name: $user->name()->value(),
            email: $user->email()->value(),
            roles: $user->roles()->names()
        );
    }
 
    /**
     * Optional helper: build an array of DTO payloads from a list of domain users.
     *
     * @param iterable<DomainUser> $users
     *
     * @return array<int, array<string, mixed>>
     */
    public static function collection(iterable $users): array
    {
        $data = [];

        foreach ($users as $user) {
            $data[] = self::fromEntity($user)->toArray();
        }

        return $data;
    }

    /**
     * Convert the DTO to an array suitable for JSON responses.
     *
     * @return array{
     *     id: string,
     *     name: string,
     *     email: string,
     *     roles: string[]
     * }
     */
    public function toArray(): array
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'email' => $this->email,
            'roles' => $this->roles,
        ];
    }
}
