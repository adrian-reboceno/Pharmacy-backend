<?php
#app/Application/User/DTOs/V1/UserDTO.php
namespace App\Application\User\DTOs\V1;

use App\Models\User;

class UserDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public array $permissions = [],
        public ?string $role = null
    ) {}

    public static function fromModel(User $user): self
    {
        return new self(
            $user->id,
            $user->name,
            $user->email,
            $user->getDirectPermissions()->pluck('name')->toArray(),
            $user->getRoleNames()->first() // obtenemos el primer rol asignado
        );
    }

    public function toArray(): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'email'       => $this->email,
            'permissions' => $this->permissions,
            'role'        => $this->role,
        ];
    }
}
