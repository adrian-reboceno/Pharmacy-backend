<?php
# src/Application/User/UseCases/V1/ShowUser.php

namespace App\Application\User\UseCases\V1;

use App\Domain\User\Entities\User;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\UserId;
use App\Shared\Domain\Exceptions\NotFoundException;

/**
 * Use Case: ShowUser
 *
 * Retrieves detailed information of a specific user identified
 * by their unique ID. This use case represents a query operation
 * within the Application layer and is responsible for orchestrating
 * user data retrieval from the domain repository.
 *
 * It ensures that domain rules are respected and that an explicit
 * error is raised when the requested user does not exist.
 */
final class ShowUser
{
    /**
     * Repository responsible for retrieving user data.
     */
    public function __construct(
        private readonly UserRepositoryInterface $repository
    ) {
    }

    /**
     * Execute the user retrieval process.
     *
     * Attempts to find a specific user by its unique identifier.
     * If the user cannot be found, a NotFoundException is thrown
     * to indicate that the requested resource does not exist.
     *
     * @param string|int $id
     *        The unique identifier of the user to retrieve.
     *
     * @return User
     *         The corresponding User entity if found.
     *
     * @throws NotFoundException
     *         When no user with the specified ID exists.
     *
     * @example
     * php
     * try {
     *     $user = $showUser->execute(42);
     *     // use $user...
     * } catch (NotFoundException $e) {
     *     // handle "user not found"
     * }
     * 
     */
    public function execute(string|int $id): User
    {
        $userId = new UserId($id);

        // Attempt to find the user by ID
        $user = $this->repository->findById($userId);

        // If the user does not exist, throw an application-level exception
        if ($user === null) {
            throw new NotFoundException(
                "The User with ID {$userId->value()} does not exist. Please verify."
            );
        }

        return $user;
    }
}
