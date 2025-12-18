<?php
# src/Application/Role/UseCases/V1/ListRoles.php

namespace App\Application\Role\UseCases\V1;

use App\Application\Role\DTOs\V1\ListRolesDTO;
use App\Domain\Role\Entities\Role;
use App\Domain\Role\Repositories\RoleRepositoryInterface;
use App\Shared\Application\Pagination\PaginatedResult;

/**
 * Use Case: ListRoles
 *
 * Orquesta la obtención paginada de roles desde el dominio.
 * No conoce nada de Eloquent ni del framework; delega todo
 * al RoleRepositoryInterface y devuelve un PaginatedResult<Role>.
 */
final class ListRoles
{
    public function __construct(
        private readonly RoleRepositoryInterface $repository,
    ) {}

    /**
     * Ejecuta el listado paginado de roles.
     */
    public function execute(ListRolesDTO $dto): PaginatedResult
    {
        $page    = $dto->page;
        $perPage = $dto->perPage;

        // 1) Obtener los roles para la página solicitada
        /** @var Role[] $items */
        $items = $this->repository->paginate($page, $perPage);

        // 2) Total de registros
        $total = $this->repository->count();

        // 3) Construir el resultado paginado
        return new PaginatedResult(
            items:   $items,
            total:   $total,
            page:    $page,
            perPage: $perPage,
        );
    }
}
