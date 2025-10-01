<?php
# app/Application/Role/UseCases/V1/ListRoles.php

namespace App\Application\Role\UseCases\V1;

use App\Domain\Role\Repositories\RoleRepositoryInterface;
use App\Presentation\DTOs\V1\RoleDTO;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Use Case: Retrieve a paginated list of roles.
 *
 * This use case encapsulates the logic to list roles from the system,
 * ordered by their unique identifier. It converts each Role model into
 * a RoleDTO for consistency in the presentation layer.
 *
 * Principles applied:
 * - **SRP (Single Responsibility Principle):** Handles only the retrieval of roles.
 * - **DIP (Dependency Inversion Principle):** Relies on the abstraction
 *   `RoleRepositoryInterface`, decoupling it from the persistence details.
 *
 * Example usage in a Controller:
 * ```php
 * use App\Application\Role\UseCases\V1\ListRoles;
 *
 * class RoleController
 * {
 *     public function index(Request $request, ListRoles $listRoles)
 *     {
 *         $perPage = $request->input('per_page', 10);
 *         $roles = $listRoles->handle($perPage);
 *
 *         return response()->json($roles);
 *     }
 * }
 * ```
 */
class ListRoles
{
    /**
     * Constructor.
     *
     * @param RoleRepositoryInterface $repo Repository responsible for role persistence.
     */
    public function __construct(protected RoleRepositoryInterface $repo) {}

    /**
     * Executes the process of retrieving roles in a paginated format.
     *
     * @param int $perPage Number of roles per page (default: 10).
     *
     * @return LengthAwarePaginator Paginated list of RoleDTO objects.
     */
    public function handle(int $perPage = 10): LengthAwarePaginator
    {
        $paginator = $this->repo->query()
            ->orderBy('id', 'asc')
            ->paginate($perPage);

        // Transform each Role model into a DTO
        $paginator->getCollection()->transform(
            fn($role) => RoleDTO::fromModel($role)
        );

        return $paginator;
    }
}
