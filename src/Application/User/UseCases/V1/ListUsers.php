<?php
# src/Application/User/UseCases/V1/ListUsers.php

namespace App\Application\User\UseCases\V1;

use App\Application\User\DTOs\V1\ListUsersDTO;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Shared\Application\Pagination\PaginatedResult;
use App\Domain\User\Entities\User;

/**
 * Use Case: ListUsers
 *
 * Devuelve una lista paginada de usuarios como PaginatedResult<User>.
 */
final class ListUsers
{
    public function __construct(
        private readonly UserRepositoryInterface $repository
    ) {
    }

    /**
     * @return PaginatedResult<User>
     */
    public function execute(ListUsersDTO $dto): PaginatedResult
    {
        $items = $this->repository->paginate($dto->page, $dto->perPage);
        $total = $this->repository->count();

        return new PaginatedResult(
            items: $items,
            total: $total,
            page: $dto->page,
            perPage: $dto->perPage,
        );
    }
}
