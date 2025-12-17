<?php
# src/Domain/Permission/Repositories/PermissionRepositoryInterface.php

namespace App\Domain\Permission\Repositories;

use App\Domain\Permission\Entities\Permission;
use App\Domain\Permission\ValueObjects\PermissionId;
use App\Domain\Permission\ValueObjects\PermissionName;

interface PermissionRepositoryInterface
{
    public function findById(PermissionId $id): ?Permission;

    public function findByName(PermissionName $name): ?Permission;

    /**
     * @return Permission[]
     */
    public function paginate(int $page, int $perPage): array;

    public function count(): int;

    public function save(Permission $permission): Permission;

    public function delete(PermissionId $id): void;
}
