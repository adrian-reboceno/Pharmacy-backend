<?php
# app/Presentation/DTOs/V1/UserDTO.php
namespace App\Presentation\DTOs\V1;

use App\Domain\User\Entities\User;

class UserDTO
{
    public string $name;
    public string $email;
    public ?string $role;

    public function __construct(string $name, string $email, ?string $role = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;
    }

    public static function fromEntity(User $user): self
    {
        return new self(
            name: $user->name,
            email: $user->email,
            role: $user->role
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];
    }
}
