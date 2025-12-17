<?php
# app/Presentation/DTOs/V1/Permission/PermissionResponseDTO.php

namespace App\Presentation\DTOs\V1\Permission;

use App\Domain\Permission\Entities\Permission as DomainPermission;

final class PermissionResponseDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $guardName = null,
    ) {}

    public static function fromEntity(DomainPermission $permission): self
    {
        return new self(
            id: $permission->id()->value(),
            name: $permission->name()->value(),
            guardName: $permission->guardName()?->value()
        );
    }

    /**
     * @return array{id:int,name:string,guard_name:?string}
     */
    public function toArray(): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'guard_name' => $this->guardName,
        ];
    }
}
