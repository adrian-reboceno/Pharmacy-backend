<?php

// app/Application/User/UseCases/V1/ShowUser.php

namespace App\Application\User\UseCases\V1;

use App\Domain\User\Entities\User;
use App\Domain\User\Repositories\UserRepositoryInterface;
use Illuminate\Http\Response;

/**
 * ──────────────────────────────────────────────────────────────
 * Use Case: ShowUser
 * ──────────────────────────────────────────────────────────────
 *
 * @purpose
 * Retrieves detailed information of a specific user identified
 * by their unique ID. This use case represents a *query operation*
 * within the application layer and is responsible for orchestrating
 * user data retrieval from the domain repository.
 *
 * It ensures that domain rules are respected and appropriate
 * error handling is applied when the requested user does not exist.
 *
 * ──────────────────────────────────────────────────────────────
 *
 * @layer Application
 *
 * @pattern Query Use Case (DDD)
 *
 * @version 1.0
 *
 * @author AFLR
 */
final class ShowUser
{
    /**
     * Repository responsible for retrieving user data.
     */
    private readonly UserRepositoryInterface $repository;

    /**
     * Constructor.
     *
     * @param  UserRepositoryInterface  $repository
     *                                               Repository abstraction for retrieving user domain entities.
     */
    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Execute the user retrieval process.
     *
     * Attempts to find a specific user by its unique identifier.
     * If the user cannot be found, a `RuntimeException` is thrown
     * with a 404 (Not Found) status code to indicate the resource
     * is missing.
     *
     * @param  int  $id
     *                   The unique identifier of the user to retrieve.
     * @return User|null
     *                   Returns the corresponding User entity if found,
     *                   or `null` if the user does not exist.
     *
     * @throws \RuntimeException
     *                           Thrown when no user with the specified ID exists.
     *
     * ──────────────────────────────────────────────
     * Example usage:
     * ──────────────────────────────────────────────
     * ```php
     * $useCase = new ShowUser($userRepository);
     *
     * try {
     *     $user = $useCase->handle(42);
     *     echo $user->name()->value();
     * } catch (\RuntimeException $e) {
     *     // Handle 404 not found error
     *     echo $e->getMessage();
     * }
     * ```
     *
     * ──────────────────────────────────────────────
     * Design principles:
     *  - **Single Responsibility:** Handles only user retrieval.
     *  - **Separation of Concerns:** Delegates persistence logic to the repository.
     *  - **Error Transparency:** Throws explicit exception when resource is missing.
     * ──────────────────────────────────────────────
     */
    public function handle(int $id): ?User
    {
        // Attempt to find the user by ID
        $user = $this->repository->find($id);

        // If the user does not exist, throw a domain-level exception
        if (! $user) {
            throw new \RuntimeException(
                "The User with ID {$id} does not exist. Please verify.",
                Response::HTTP_NOT_FOUND // 404
            );
        }

        return $user;
    }
}
