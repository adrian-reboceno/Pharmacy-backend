<?php
# src/Application/Role/UseCases/V1/CreateRole.php

namespace App\Application\Role\UseCases\V1;

use App\Application\Role\DTOs\V1\CreateRoleDTO;
use App\Domain\Role\Entities\Role;
use App\Domain\Role\Repositories\RoleRepositoryInterface;
use App\Domain\Role\ValueObjects\RoleName;
use App\Domain\Role\ValueObjects\RoleGuardName;
use App\Shared\Domain\Exceptions\AlreadyExistsException;

/**
 * Use Case: CreateRole
 */
final class CreateRole
{
    public function __construct(
        private readonly RoleRepositoryInterface $repository
    ) {}

    public function execute(CreateRoleDTO $dto): Role
    {
        $nameVo  = new RoleName($dto->name);
        $guardVo = new RoleGuardName($dto->guardName);

        // ¿ya existe rol con ese name+guard?
        $existing = $this->repository->findByName($nameVo, $guardVo);
        if ($existing !== null) {
            throw new AlreadyExistsException(
                "A role with name '{$nameVo->value()}' and guard '{$guardVo->value()}' already exists."
            );
        }

        $role = new Role(
            id: null,
            name: $nameVo,
            guardName: $guardVo,
            permissions: $dto->permissions,
        );

        return $this->repository->save($role);
    }
}
