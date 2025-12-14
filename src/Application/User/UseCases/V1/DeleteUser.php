<?php

// src/Application/User/UseCases/V1/DeleteUser.php

namespace App\Application\User\UseCases\V1;

use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\UserId;
use App\Shared\Domain\Exceptions\NotFoundException;

/**
 * Use Case: DeleteUser
 *
 * Handles the removal of a user from the system by its unique identifier.
 *
 * This use case serves as an entry point in the Application layer for
 * deleting a user. It ensures that the user exists in the system before
 * delegating the removal to the persistence layer through the repository.
 *
 * It does not deal with HTTP details (status codes, responses, etc.);
 * those concerns are handled in the Presentation layer.
 */
final class DeleteUser
{
    /**
     * Repository responsible for user persistence operations.
     */
    public function __construct(
        private readonly UserRepositoryInterface $repository
    ) {}

    /**
     * Execute the user deletion process.
     *
     * Flow:
     *  1. Build a UserId value object from the raw identifier.
     *  2. Check if the user exists in the repository.
     *     - If not found, throw a NotFoundException.
     *  3. Delegate deletion to the repository.
     *
     * @param  string|int  $id
     *                          The unique identifier of the user to delete.
     *
     * @throws NotFoundException
     *                           When the user with the specified ID does not exist.
     *
     * @example
     * php
     * try {
     *     $useCase->execute(42);
     *     // success, user deleted
     * } catch (NotFoundException $e) {
     *     // handle "user not found" case
     * }
     */
    public function execute(string|int $id): void
    {
        $userId = new UserId($id);

        // Check if the user exists in the repository.
        $user = $this->repository->findById($userId);

        if ($user === null) {
            throw new NotFoundException(
                "The User with ID {$userId->value()} does not exist and cannot be deleted."
            );
        }

        // Proceed with deletion through the repository abstraction.
        $this->repository->delete($userId);
    }
}
