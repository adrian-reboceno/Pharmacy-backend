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
     * @var int The unique identifier of the user.
     */
    public int $id;

    /**
     * @var string The user's full name.
     */
    public string $name;

    /**
     * @var string The user's email address.
     */
    public string $email;

    /**
     * @var array List of role names assigned to the user.
     */
    public array $roles;

    /**
     * Constructor
     *
     * @param int $id
     * @param string $name
     * @param string $email
     * @param array $roles
     */
    public function __construct(int $id, string $name, string $email, array $roles)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->roles = $roles;
    }

    /**
     * Create a UserResponseDTO from a domain User entity.
     *
     * @param DomainUser $user
     * @return self
     */
    public static function fromEntity(DomainUser $user): self
    {
        return new self(
            $user->id()->value(),
            $user->name()->value(),
            $user->email()->value(),
            $user->roles()->names()
        );
    }

    /**
     * Convert the DTO to an array suitable for JSON responses.
     *
     * @return array{
     *     id: int,
     *     name: string,
     *     email: string,
     *     roles: string[]
     * }
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'roles' => $this->roles,
        ];
    }
}
