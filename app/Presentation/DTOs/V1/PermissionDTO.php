<?php
#app/Presentation/DTOs/V1/PermissionDTO.php
namespace App\Presentation\DTOs\V1;

use Spatie\Permission\Models\Permission;

class PermissionDTO
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

    public static function fromModel(Permission $permission): self
    {
        return new self(
            $permission->id,
            $permission->name,
            $permission->guard_name
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
