<?php
# src/Domain/Role/Repositories/RoleRepositoryInterface.php

namespace App\Domain\Role\Repositories;

use App\Domain\Role\Entities\Role;
use App\Domain\Role\ValueObjects\RoleId;
use App\Domain\Role\ValueObjects\RoleName;
use App\Domain\Role\ValueObjects\RoleGuardName;

/**
 * RoleRepositoryInterface
 *
 * Contrato de persistencia para Roles (independiente de Eloquent/Spatie).
 */
interface RoleRepositoryInterface
{
    public function findById(RoleId $id): ?Role;

    public function findByName(RoleName $name): ?Role;

    /**
     * @return Role[]
     */
    public function paginate(int $page, int $perPage): array;

    public function count(): int;

    public function save(Role $role): Role;

    public function delete(RoleId $id): void;
}
