<?php
# app/Presentation/DTOs/V1/UserDTO.php
namespace App\Presentation\DTOs\V1;

use App\Domain\Auth\Entities\User;

class AuthUserDTO
{
    public int $id;
    public string $name;
    public string $email;
    public ?string $role;

    public function __construct(int $id, string $name, string $email, ?string $role = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;
    }

    public static function fromEntity(User $user): self
    {
        return new self(
            id :$user->id,
            name: $user->name,
            email: $user->email,
            role: $user->role
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];
    }
}
