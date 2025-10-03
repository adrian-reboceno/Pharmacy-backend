<?php
# app/Presentation/DTOs/V1/UserDTO.php

namespace App\Presentation\DTOs\V1;

use App\Domain\Auth\Entities\User;

/**
 * Data Transfer Object: AuthUserDTO
 *
 * Represents a simplified, presentation-ready view of a User entity.
 * Used to ensure clean and consistent responses in the API layer,
 * without exposing domain or infrastructure details directly.
 */
class AuthUserDTO
{
    /**
     * The unique identifier of the user.
     *
     * @var int
     */
    public int $id;

    /**
     * The full name of the user.
     *
     * @var string
     */
    public string $name;

    /**
     * The email address of the user.
     *
     * @var string
     */
    public string $email;

    /**
     * The role assigned to the user (if any).
     *
     * @var string|null
     */
    public ?string $role;

    /**
     * Create a new AuthUserDTO instance.
     *
     * @param int         $id    The user’s unique identifier.
     * @param string      $name  The user’s full name.
     * @param string      $email The user’s email address.
     * @param string|null $role  The user’s role (optional).
     */
    public function __construct(int $id, string $name, string $email, ?string $role = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;
    }

    /**
     * Create a DTO instance from a domain User entity.
     *
     * @param User $user The domain User entity.
     *
     * @return self A DTO representing the user.
     */
    public static function fromEntity(User $user): self
    {
        return new self(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            role: $user->role
        );
    }

    /**
     * Convert the DTO into an array format for API responses.
     *
     * @return array<string, mixed> Array containing:
     *                              - 'id'    => int
     *                              - 'name'  => string
     *                              - 'email' => string
     *                              - 'role'  => string|null
     */
    public function toArray(): array
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'email' => $this->email,
            'role'  => $this->role,
        ];
    }
}
