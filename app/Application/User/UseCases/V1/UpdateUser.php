<?php

// app/Application/User/UseCases/V1/UpdateUser.php

namespace App\Application\User\UseCases\V1;

use App\Application\User\DTOs\V1\UpdateUserDTO;
use App\Domain\User\Entities\User;
use App\Domain\User\Repositories\UserRepositoryInterface;
use Illuminate\Http\Response;

/**
 * ──────────────────────────────────────────────────────────────
 * Use Case: UpdateUser
 * ──────────────────────────────────────────────────────────────
 *
 * @purpose
 * Handles the update process of an existing User entity.
 * This use case is responsible for orchestrating user data updates
 * based on validated input from the DTO layer and delegating persistence
 * to the domain repository.
 *
 * It ensures that domain integrity is preserved by verifying that
 * the target user exists before applying any modifications.
 *
 * ──────────────────────────────────────────────────────────────
 *
 * @layer Application
 *
 * @pattern Command Use Case (DDD)
 *
 * @version 1.0
 *
 * @author AFLR
 */
final class UpdateUser
{
    /**
     * Repository abstraction for persisting and retrieving User entities.
     */
    private readonly UserRepositoryInterface $repository;

    /**
     * Constructor.
     *
     * @param  UserRepositoryInterface  $repository
     *                                               Repository responsible for updating user domain entities.
     */
    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Execute the user update process.
     *
     * This method retrieves an existing User entity by ID, validates
     * its existence, and applies the provided updates using data from
     * the `UpdateUserDTO`. If the user cannot be found, a runtime
     * exception is thrown.
     *
     * @param  int  $id
     *                   The unique identifier of the user to update.
     * @param  UpdateUserDTO  $dto
     *                              A Data Transfer Object containing the updated user data.
     * @return User
     *              Returns the updated domain User entity.
     *
     * @throws \RuntimeException
     *                           Thrown when no user with the given ID exists.
     *
     * @example
     * ```php
     * $useCase = new UpdateUser($userRepository);
     *
     * $dto = new UpdateUserDTO(
     *     name: 'John Doe',
     *     email: 'john.doe@example.com',
     *     password: 'new_secure_password',
     *     roles: ['admin']
     * );
     *
     * try {
     *     $updatedUser = $useCase->handle(42, $dto);
     *     echo "User updated: " . $updatedUser->name()->value();
     * } catch (\RuntimeException $e) {
     *     echo $e->getMessage(); // "User with ID 42 not found."
     * }
     * ```
     *
     * ──────────────────────────────────────────────
     * Design principles:
     *  - **Command Pattern:** Represents an intention to modify system state.
     *  - **Single Responsibility:** Only handles updating user data.
     *  - **Repository Delegation:** Keeps infrastructure details isolated.
     *  - **Exception Safety:** Throws domain-meaningful errors for missing resources.
     * ──────────────────────────────────────────────
     */
    public function handle(int $id, UpdateUserDTO $dto): User
    {
        // Attempt to retrieve the user by ID from the repository
        $user = $this->repository->find($id);

        // Validate that the user exists
        if (! $user) {
            throw new \RuntimeException(
                "User with ID {$id} not found.",
                Response::HTTP_NOT_FOUND // 404
            );
        }

        // Delegate persistence update to the repository
        return $this->repository->updateById($id, $dto->toArray());
    }
}
