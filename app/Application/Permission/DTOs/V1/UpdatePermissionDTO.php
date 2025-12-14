<?php

// app/Application/Permission/DTOs/V1/UpdatePermissionDTO.php

namespace App\Application\Permission\DTOs\V1;

class UpdatePermissionDTO
{
    public string $name;

    public string $guard_name;

    public function __construct(array $data)
    {
        $this->name = $data['name'] ?? '';
        $this->guard_name = $data['guard_name'] ?? 'api';
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'guard_name' => $this->guard_name,
        ];
    }
}
