<?php
# src/Application/Permission/UseCases/V1/UpdatePermission.php

namespace App\Application\Permission\UseCases\V1;

use App\Application\Permission\DTOs\V1\UpdatePermissionDTO;
use App\Domain\Permission\Entities\Permission as DomainPermission;
use App\Domain\Permission\Repositories\PermissionRepositoryInterface;
use App\Domain\Permission\ValueObjects\PermissionId;
use App\Domain\Permission\ValueObjects\PermissionName;
use App\Domain\Permission\ValueObjects\PermissionGuardName;
use App\Shared\Domain\Exceptions\NotFoundException;

final class UpdatePermission
{
    public function __construct(
        private readonly PermissionRepositoryInterface $repository,
    ) {}

    public function execute(UpdatePermissionDTO $dto): DomainPermission
    {
        $idVo = new PermissionId($dto->id);

        $permission = $this->repository->findById($idVo);

        if ($permission === null) {
            throw new NotFoundException("Permission with ID {$dto->id} not found.");
        }

        if ($dto->name !== null) {
            $permission->rename(new PermissionName($dto->name));
        }

        if ($dto->guardName !== null) {
            $permission->changeGuardName(new PermissionGuardName($dto->guardName));
        }

        return $this->repository->save($permission);
    }
}
