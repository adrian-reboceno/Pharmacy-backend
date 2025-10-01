<?php
# app/Application/Permission/UseCases/V1/DeletePermission.php
namespace App\Application\Permission\UseCases\V1;

use App\Domain\Permission\Repositories\PermissionRepositoryInterface;
use Illuminate\Http\Response;

/**
 * Use Case: Delete an existing permission from the system.
 *
 * This use case encapsulates the business logic to remove a permission
 * by its unique identifier (ID). It ensures the permission exists before deletion.
 *
 * Applied principles:
 * - **SRP (Single Responsibility Principle):** Responsible only for deleting permissions.
 * - **DIP (Dependency Inversion Principle):** Depends on an abstraction
 *   (`PermissionRepositoryInterface`) instead of a concrete implementation.
 *
 * Example usage in a Controller:
 * ```php
 * use App\Application\Permission\UseCases\V1\DeletePermission;
 *
 * class PermissionController
 * {
 *     public function destroy(int $id, DeletePermission $deletePermission)
 *     {
 *         $deletePermission->handle($id);
 *         return response()->json(['message' => 'Permission deleted successfully'], 200);
 *     }
 * }
 * ```
 */
class DeletePermission
{
    /**
     * Permission repository for domain persistence operations.
     *
     * @var PermissionRepositoryInterface
     */
    protected PermissionRepositoryInterface $repo;

    /**
     * Constructor.
     *
     * @param PermissionRepositoryInterface $repo Repository handling permission persistence.
     */
    public function __construct(PermissionRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Executes the deletion of a permission by its ID.
     *
     * @param int $id Unique identifier of the permission to be deleted.
     *
     * @throws \RuntimeException If the permission does not exist in the system.
     *
     * @return bool True if the permission was successfully deleted, false otherwise.
     */
    public function handle(int $id): bool
    {
        // Attempt to find the permission by ID
        $permission = $this->repo->find($id);

        // Throw an exception if the permission does not exist
        if (!$permission) {
            throw new \RuntimeException(
                "The permission with ID {$id} does not exist and cannot be deleted. Please verify.",
                Response::HTTP_NOT_FOUND // 404
            );
        }

        // Proceed with deletion at the domain layer
        return $this->repo->delete($id);
    }
}
