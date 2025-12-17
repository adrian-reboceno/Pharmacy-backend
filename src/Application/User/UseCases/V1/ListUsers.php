<?php

// src/Application/User/UseCases/V1/ListUsers.php

namespace App\Application\User\UseCases\V1;

use App\Application\User\DTOs\V1\ListUsersDTO;
use App\Domain\User\Entities\User;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Shared\Application\Pagination\PaginatedResult;

/**
 * Use Case: ListUsers
 *
 * Returns a paginated list of Users.
 *
 * This use case lives in the Application layer and delegates
 * data access to the UserRepositoryInterface, keeping the
 * Application independent from any specific ORM or framework.
 */
final class ListUsers
{
    /**
     * Repository responsible for retrieving user data.
     */
    public function __construct(
        private readonly UserRepositoryInterface $repository
    ) {}

    /**
     * Execute the user listing process.
     *
     * Builds a paginated result using the repository and returns
     * a PaginatedResult value object that can be easily transformed
     * into arrays or JSON by the Presentation layer.
     *
     * @param  ListUsersDTO  $dto
     *                             Contains pagination parameters (page, perPage).
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
            perPage: $dto->perPage
        );
    }
}
