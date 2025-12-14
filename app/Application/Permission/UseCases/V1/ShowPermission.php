<?php

// app/Application/Permission/UseCases/V1/ShowPermission.php

namespace App\Application\Permission\UseCases\V1;

use App\Domain\Permission\Repositories\PermissionRepositoryInterface;
use App\Presentation\DTOs\V1\PermissionDTO;
use Illuminate\Http\Response;

/**
 * Use Case: Retrieve a specific permission by its ID.
 *
 * This use case encapsulates the business logic to fetch and return
 * a single permission from the system, ensuring that the permission exists.
 *
 * Applied principles:
 * - **SRP (Single Responsibility Principle):** Responsible only for retrieving a permission by ID.
 * - **DIP (Dependency Inversion Principle):** Depends on an abstraction
 *   (`PermissionRepositoryInterface`) instead of a concrete implementation.
 *
 * Example usage in a Controller:
 * ```php
 * use App\Application\Permission\UseCases\V1\ShowPermission;
 *
 * class PermissionController
 * {
 *     public function show(int $id, ShowPermission $showPermission)
 *     {
 *         $permissionDTO = $showPermission->handle($id);
 *         return response()->json($permissionDTO, 200);
 *     }
 * }
 * ```
 */
class ShowPermission
{
    /**
     * Permission repository for domain persistence operations.
     */
    protected PermissionRepositoryInterface $repo;

    /**
     * Constructor.
     *
     * @param  PermissionRepositoryInterface  $repo  Repository handling permission persistence.
     */
    public function __construct(PermissionRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Executes the retrieval of a permission by its ID.
     *
     * @param  int  $id  Unique identifier of the permission to be retrieved.
     * @return PermissionDTO Data Transfer Object representing the requested permission.
     *
     * @throws \RuntimeException If the permission does not exist in the system.
     */
    public function handle(int $id): PermissionDTO
    {
        // Attempt to find the permission by ID
        $permission = $this->repo->find($id);

        // Throw an exception if the permission does not exist
        if (! $permission) {
            throw new \RuntimeException(
                "The permission with ID {$id} does not exist. Please verify.",
                Response::HTTP_NOT_FOUND // 404
            );
        }

        // Return DTO for presentation layer
        return PermissionDTO::fromModel($permission);
    }
}
