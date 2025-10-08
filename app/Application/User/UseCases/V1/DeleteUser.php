<?php
# app/Application/User/UseCases/V1/DeleteUser.php

namespace App\Application\User\UseCases\V1;

use App\Domain\User\Repositories\UserRepositoryInterface;
use Illuminate\Http\Response;

/**
 * ──────────────────────────────────────────────────────────────
 * Use Case: DeleteUser
 * ──────────────────────────────────────────────────────────────
 *
 * @purpose
 * Handles the removal of a user from the system by its unique identifier.
 *
 * This use case serves as an entry point in the *application layer* for
 * deleting a user. It ensures that the user exists in the system before
 * delegating the removal to the persistence layer through the repository.
 *
 * By isolating this operation as a use case, the system maintains separation
 * between domain logic and infrastructure implementation details, ensuring
 * that deletion respects business rules and consistency boundaries.
 *
 * ──────────────────────────────────────────────────────────────
 * @layer Application
 * @pattern Command Use Case (DDD)
 * @version 1.0
 * @author AFLR
 * @package App\Application\User\UseCases\V1
 * ──────────────────────────────────────────────────────────────
 */
final class DeleteUser
{
    /**
     * Repository responsible for user persistence operations.
     *
     * @var UserRepositoryInterface
     */
    private readonly UserRepositoryInterface $repository;

    /**
     * Constructor.
     *
     * @param UserRepositoryInterface $repository
     *        Repository implementation handling data persistence and retrieval.
     */
    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Execute the user deletion process.
     *
     * This method verifies the existence of the user before attempting deletion.
     * If the user does not exist, a `RuntimeException` is thrown to signal that
     * the requested entity cannot be found. Otherwise, it proceeds to remove
     * the user using the repository abstraction.
     *
     * @param int $id
     *        The unique identifier of the user to delete.
     *
     * @return bool
     *         Returns `true` if the user was successfully deleted, otherwise `false`.
     *
     * @throws \RuntimeException
     *         Thrown when the user with the specified ID does not exist.
     *
     * ──────────────────────────────────────────────
     * Example usage:
     * ──────────────────────────────────────────────
     * ```php
     * $useCase = new DeleteUser($userRepository);
     * 
     * try {
     *     $deleted = $useCase->handle(5);
     *     if ($deleted) {
     *         echo "User deleted successfully.";
     *     }
     * } catch (\RuntimeException $e) {
     *     echo $e->getMessage();
     * }
     * ```
     *
     * ──────────────────────────────────────────────
     * This ensures that business logic for deletion remains centralized
     * and can be extended (e.g., soft deletes, event publishing, etc.)
     * without impacting external layers.
     * ──────────────────────────────────────────────
     */
    public function handle(int $id): bool
    {
        // Attempt to locate the user in the repository.
        $user = $this->repository->find($id);

        // Throw an exception if the user does not exist.
        if (!$user) {
            throw new \RuntimeException(
                "The User with ID {$id} does not exist and cannot be deleted. Please verify.",
                Response::HTTP_NOT_FOUND // 404
            );
        }

        // Proceed with deletion through the repository abstraction.
        return $this->repository->deleteById($id);
    }
}
