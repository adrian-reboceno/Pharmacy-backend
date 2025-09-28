<?php
#app/Presentation/DTOs/V1/RoleDTO.php
namespace App\Presentation\DTOs\V1;

use Spatie\Permission\Models\Role;

class RoleDTO
{
    public int $id;
    public string $name;
    public string $guard_name;

    public function __construct(int $id, string $name, string $guard_name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->guard_name = $guard_name;
    }

    public static function fromModel(Role $role): self
    {
        return new self(
            $role->id,
            $role->name,
            $role->guard_name
        );
    }

    public function toArray(): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'guard_name' => $this->guard_name,
        ];
    }
}
