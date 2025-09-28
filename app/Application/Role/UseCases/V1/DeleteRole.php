<?php
# app/Application/Role/UseCases/V1/DeleteRole.php

namespace App\Application\Role\UseCases\V1;

use App\Domain\Role\Repositories\RoleRepositoryInterface;
use Illuminate\Http\Response;

/**
 * Use Case: Delete an existing role from the system.
 *
 * This use case encapsulates the business logic to remove a role
 * from the system by its unique identifier (ID).
 * It ensures that the role exists before attempting deletion.
 *
 * Applied principles:
 * - **SRP (Single Responsibility Principle):** Responsible only for deleting roles.
 * - **DIP (Dependency Inversion Principle):** Depends on an abstraction
 *   (`RoleRepositoryInterface`) instead of a concrete implementation.
 *
 * Example usage in a Controller:
 * ```php
 * use App\Application\Role\UseCases\V1\DeleteRole;
 *
 * class RoleController
 * {
 *     public function destroy(int $id, DeleteRole $deleteRole)
 *     {
 *         $deleteRole->handle($id);
 *         return response()->json(['message' => 'Role deleted successfully'], 200);
 *     }
 * }
 * ```
 */
class DeleteRole
{
    /**
     * Role repository for domain persistence operations.
     *
     * @var RoleRepositoryInterface
     */
    protected RoleRepositoryInterface $repo;

    /**
     * Constructor.
     *
     * @param RoleRepositoryInterface $repo Repository handling role persistence.
     */
    public function __construct(RoleRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Executes the deletion of a role by its ID.
     *
     * @param int $id Unique identifier of the role to be deleted.
     *
     * @throws \RuntimeException If the role does not exist in the system.
     *
     * @return bool True if the role was successfully deleted, false otherwise.
     */
    public function handle(int $id): bool
    {
        // Attempt to find the role by ID
        $role = $this->repo->find($id);

        // Throw an exception if the role does not exist
        if (!$role) {
            throw new \RuntimeException(
                "The role with ID {$id} does not exist and cannot be deleted. Please verify.",
                Response::HTTP_NOT_FOUND // 404
            );
        }

        // Proceed with deletion at the domain layer
        return $this->repo->delete($id);
    }
}
