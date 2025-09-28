<?php
# app/Application/Role/UseCases/V1/ShowRole.php

namespace App\Application\Role\UseCases\V1;

use App\Domain\Role\Repositories\RoleRepositoryInterface;
use App\Presentation\DTOs\V1\RoleDTO;
use Illuminate\Http\Response;

/**
 * Use Case: Retrieve a specific role by its ID.
 *
 * This use case encapsulates the business logic to fetch and return
 * a single role from the system, ensuring that the role exists.
 *
 * Applied principles:
 * - **SRP (Single Responsibility Principle):** Responsible only for retrieving a role by ID.
 * - **DIP (Dependency Inversion Principle):** Depends on an abstraction
 *   (`RoleRepositoryInterface`) instead of a concrete implementation.
 *
 * Example usage in a Controller:
 * ```php
 * use App\Application\Role\UseCases\V1\ShowRole;
 *
 * class RoleController
 * {
 *     public function show(int $id, ShowRole $showRole)
 *     {
 *         $roleDTO = $showRole->handle($id);
 *         return response()->json($roleDTO, 200);
 *     }
 * }
 * ```
 */
class ShowRole
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
     * Executes the retrieval of a role by its ID.
     *
     * @param int $id Unique identifier of the role to be retrieved.
     *
     * @throws \RuntimeException If the role does not exist in the system.
     *
     * @return RoleDTO Data Transfer Object representing the requested role.
     */
    public function handle(int $id): RoleDTO
    {
        // Attempt to find the role by ID
        $role = $this->repo->find($id);

        // Throw an exception if the role does not exist
        if (!$role) {
            throw new \RuntimeException(
                "The role with ID {$id} does not exist. Please verify.",
                Response::HTTP_NOT_FOUND // 404
            );
        }

        // Return DTO for presentation layer
        return RoleDTO::fromModel($role);
    }
}
