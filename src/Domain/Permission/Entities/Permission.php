<?php
# src/Domain/Permission/Entities/Permission.php

namespace App\Domain\Permission\Entities;

use App\Domain\Permission\ValueObjects\PermissionId;
use App\Domain\Permission\ValueObjects\PermissionName;
use App\Domain\Permission\ValueObjects\PermissionGuardName;

final class Permission
{
    public function __construct(
        private ?PermissionId $id,
        private PermissionName $name,
        private PermissionGuardName $guardName,
    ) {}

    public function id(): ?PermissionId
    {
        return $this->id;
    }

    public function name(): PermissionName
    {
        return $this->name;
    }

    public function guardName(): PermissionGuardName
    {
        return $this->guardName;
    }

    public function rename(PermissionName $name): void
    {
        $this->name = $name;
    }

    public function changeGuardName(PermissionGuardName $guardName): void
    {
        $this->guardName = $guardName;
    }
}
