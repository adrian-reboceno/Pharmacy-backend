<?php
# src/Application/Permission/UseCases/V1/DeletePermission.php

namespace App\Application\Permission\UseCases\V1;

use App\Domain\Permission\Repositories\PermissionRepositoryInterface;
use App\Domain\Permission\ValueObjects\PermissionId;
use App\Shared\Domain\Exceptions\NotFoundException;

/**
 * Use Case: DeletePermission
 *
 * Encargado de eliminar un Permission existente identificado por su ID.
 * Verifica su existencia antes de delegar la eliminación al repositorio.
 */
final class DeletePermission
{
    public function __construct(
        private readonly PermissionRepositoryInterface $repository
    ) {
    }

    /**
     * Ejecuta el proceso de eliminación.
     *
     * @param  string|int  $id  Identificador del Permission a eliminar.
     *
     * @throws NotFoundException Cuando el Permission no existe.
     */
    public function execute(string|int $id): void
    {
        $permissionId = new PermissionId($id);

        $permission = $this->repository->findById($permissionId);

        if ($permission === null) {
            throw new NotFoundException(
                sprintf('Permission with ID %s not found.', $permissionId->value())
            );
        }

        $this->repository->delete($permissionId);
    }
}
