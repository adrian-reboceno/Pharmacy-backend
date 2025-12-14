<?php

// app/Application/Role/UseCases/V1/DeleteRole.php

namespace App\Application\Role\UseCases\V1;

use App\Domain\Role\Repositories\RoleRepositoryInterface;
use Illuminate\Http\Response;

/**
 * Use Case: Delete an existing role from the system.
 *
 * This use case encapsulates the business logic required to remove a role
 * by its unique identifier (ID). It validates the existence of the role
 * before attempting deletion to ensure consistency.
 *
 * Applied principles:
 * - **SRP (Single Responsibility Principle):** Handles only the deletion of roles.
 * - **DIP (Dependency Inversion Principle):** Relies on the abstraction
 *   `RoleRepositoryInterface` rather than a concrete implementation.
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
     * Repository for role persistence operations.
     */
    protected RoleRepositoryInterface $repo;

    /**
     * Constructor.
     *
     * @param  RoleRepositoryInterface  $repo  Repository responsible for role persistence.
     */
    public function __construct(RoleRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Executes the deletion of a role by its unique identifier.
     *
     * @param  int  $id  Unique identifier of the role to delete.
     * @return bool True if the role was successfully deleted, false otherwise.
     *
     * @throws \RuntimeException If the role does not exist.
     */
    public function handle(int $id): bool
    {
        // Attempt to find the role by ID
        $role = $this->repo->find($id);

        // Throw an exception if the role does not exist
        if (! $role) {
            throw new \RuntimeException(
                "The role with ID {$id} does not exist and cannot be deleted. Please verify.",
                Response::HTTP_NOT_FOUND // 404
            );
        }

        // Proceed with deletion at the domain layer
        return $this->repo->delete($id);
    }
}
