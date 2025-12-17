<?php
# src/Application/Permission/UseCases/V1/CreatePermission.php

namespace App\Application\Permission\UseCases\V1;

use App\Application\Permission\DTOs\V1\CreatePermissionDTO;
use App\Domain\Permission\Entities\Permission as DomainPermission;
use App\Domain\Permission\Repositories\PermissionRepositoryInterface;
use App\Domain\Permission\ValueObjects\PermissionId;
use App\Domain\Permission\ValueObjects\PermissionName;
use App\Domain\Permission\ValueObjects\PermissionGuardName;

final class CreatePermission
{
    public function __construct(
        private readonly PermissionRepositoryInterface $repository,
    ) {}

    public function execute(CreatePermissionDTO $dto): DomainPermission
    {
        // Si no envías guard_name en el request, por defecto usamos 'api'
        $guardName = $dto->guard_name ?? 'api';

        $permission = new DomainPermission(
            id: null, // se genera en infraestructura
            name: new PermissionName($dto->name),
            guardName: new PermissionGuardName($guardName),
        );

        return $this->repository->save($permission);
    }
}
