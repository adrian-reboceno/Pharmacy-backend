<?php
# src/Application/Permission/UseCases/V1/ListPermissions.php

namespace App\Application\Permission\UseCases\V1;

use App\Application\Permission\DTOs\V1\ListPermissionsDTO;
use App\Domain\Permission\Entities\Permission;
use App\Domain\Permission\Repositories\PermissionRepositoryInterface;
use App\Shared\Application\Pagination\PaginatedResult;

/**
 * Use Case: ListPermissions
 *
 * Devuelve un PaginatedResult<Permission>.
 */
final class ListPermissions
{
    public function __construct(
        private readonly PermissionRepositoryInterface $repository
    ) {
    }

    /**
     * @return PaginatedResult<Permission>
     */
    public function execute(ListPermissionsDTO $dto): PaginatedResult
    {
        $items = $this->repository->paginate($dto->page, $dto->perPage);
        $total = $this->repository->count();

        return new PaginatedResult(
            items:   $items,
            total:   $total,
            page:    $dto->page,
            perPage: $dto->perPage,
        );
    }
}
