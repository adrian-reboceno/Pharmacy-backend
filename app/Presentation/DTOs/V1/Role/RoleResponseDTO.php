<?php
# app/Presentation/DTOs/V1/Role/RoleResponseDTO.php

namespace App\Presentation\DTOs\V1\Role;

use App\Domain\Role\Entities\Role as DomainRole;

/**
 * DTO para representar un rol en las respuestas de la API.
 */
final class RoleResponseDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $guardName,
        /** @var string[] */
        public array $permissions = [],
    ) {
    }

    public static function fromEntity(DomainRole $role): self
    {
        // Asumiendo entidad:
        // id()->value(), name()->value(), guardName()->value(), permissions(): array<string>
        $permissions = method_exists($role, 'permissions')
            ? $role->permissions()
            : [];

        return new self(
            $role->id()->value(),
            $role->name()->value(),
            $role->guardName()->value(),
            $permissions
        );
    }

    /**
     * @return array{id:int,name:string,guard_name:string,permissions:string[]}
     */
    public function toArray(): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'guard_name'  => $this->guardName,
            'permissions' => $this->permissions,
        ];
    }
}
