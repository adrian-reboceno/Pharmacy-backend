<?php
# src/Domain/Role/Entities/Role.php

namespace App\Domain\Role\Entities;

use App\Domain\Role\ValueObjects\RoleId;
use App\Domain\Role\ValueObjects\RoleName;
use App\Domain\Role\ValueObjects\RoleGuardName;

/**
 * Domain Entity: Role
 *
 * Representa un rol del sistema dentro del dominio.
 * No depende de Eloquent ni de Spatie, sólo de Value Objects.
 */
final class Role
{
    /**
     * @param RoleId|null      $id
     * @param RoleName         $name
     * @param RoleGuardName    $guardName
     * @param string[]         $permissions  Lista de nombres de permisos
     */
    public function __construct(
        private ?RoleId $id,
        private RoleName $name,
        private RoleGuardName $guardName,
        private array $permissions = [],
    ) {}

    public function id(): ?RoleId
    {
        return $this->id;
    }

    public function name(): RoleName
    {
        return $this->name;
    }

    public function guardName(): RoleGuardName
    {
        return $this->guardName;
    }

    /**
     * @return string[]
     */
    public function permissions(): array
    {
        return $this->permissions;
    }

    // ───────────────── Domain behavior ─────────────────

    public function rename(RoleName $name): void
    {
        $this->name = $name;
    }

    public function changeGuard(RoleGuardName $guardName): void
    {
        $this->guardName = $guardName;
    }

    /**
     * Sincroniza la lista completa de permisos por nombre.
     *
     * @param string[] $permissionNames
     */
    public function syncPermissions(array $permissionNames): void
    {
        $this->permissions = array_values(array_unique($permissionNames));
    }
}
