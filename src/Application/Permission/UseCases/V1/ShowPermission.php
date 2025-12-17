<?php
# src/Application/Permission/UseCases/V1/ShowPermission.php

namespace App\Application\Permission\UseCases\V1;

use App\Domain\Permission\Entities\Permission;
use App\Domain\Permission\Repositories\PermissionRepositoryInterface;
use App\Domain\Permission\ValueObjects\PermissionId;
use App\Shared\Domain\Exceptions\NotFoundException;

/**
 * Use Case: ShowPermission
 *
 * Recupera la información detallada de un Permission identificado por su ID.
 * Forma parte de la capa de Aplicación y orquesta la lectura desde el
 * repositorio de dominio.
 */
final class ShowPermission
{
    public function __construct(
        private readonly PermissionRepositoryInterface $repository
    ) {
    }

    /**
     * Ejecuta el proceso de consulta de un Permission.
     *
     * @param  string|int  $id  Identificador único del Permission.
     * @return Permission
     *
     * @throws NotFoundException Cuando el Permission no existe.
     */
    public function execute(string|int $id): Permission
    {
        $permissionId = new PermissionId($id);

        $permission = $this->repository->findById($permissionId);

        if ($permission === null) {
            throw new NotFoundException(
                sprintf('Permission with ID %s not found.', $permissionId->value())
            );
        }

        return $permission;
    }
}
