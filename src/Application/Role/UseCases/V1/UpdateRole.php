<?php
# src/Application/Role/UseCases/V1/UpdateRole.php

namespace App\Application\Role\UseCases\V1;

use App\Application\Role\DTOs\V1\UpdateRoleDTO;
use App\Domain\Role\Entities\Role;
use App\Domain\Role\Repositories\RoleRepositoryInterface;
use App\Domain\Role\ValueObjects\RoleId;
use App\Domain\Role\ValueObjects\RoleName;
use App\Domain\Role\ValueObjects\RoleGuardName;
use App\Shared\Domain\Exceptions\NotFoundException;

/**
 * Use Case: UpdateRole
 */
final class UpdateRole
{
    public function __construct(
        private readonly RoleRepositoryInterface $repository
    ) {}

    public function execute(UpdateRoleDTO $dto): Role
    {
        $roleId = new RoleId($dto->id);
        $role   = $this->repository->findById($roleId);

        if ($role === null) {
            throw new NotFoundException("Role with ID {$roleId->value()} not found.");
        }

        if ($dto->name !== null) {
            $role->rename(new RoleName($dto->name));
        }

        if ($dto->guardName !== null) {
            $role->changeGuard(new RoleGuardName($dto->guardName));
        }

        if ($dto->permissions !== null) {
            $role->syncPermissions($dto->permissions);
        }

        return $this->repository->save($role);
    }
}
