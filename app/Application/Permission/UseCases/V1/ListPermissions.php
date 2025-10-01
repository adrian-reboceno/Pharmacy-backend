<?php
# app/Application/Permission/UseCases/V1/ListPermissions.php
namespace App\Application\Permission\UseCases\V1;

use App\Domain\Permission\Repositories\PermissionRepositoryInterface;
use App\Presentation\DTOs\V1\PermissionDTO;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Use Case: List all permissions in the system with pagination.
 *
 * This use case encapsulates the business logic to retrieve a paginated
 * list of permissions, transforming each permission into a DTO for
 * presentation layer consumption.
 *
 * Applied principles:
 * - **SRP (Single Responsibility Principle):** Responsible only for retrieving
 *   a list of permissions.
 * - **DIP (Dependency Inversion Principle):** Depends on an abstraction
 *   (`PermissionRepositoryInterface`) instead of a concrete implementation.
 *
 * Example usage in a Controller:
 * ```php
 * use App\Application\Permission\UseCases\V1\ListPermissions;
 *
 * class PermissionController
 * {
 *     public function index(ListPermissions $listPermissions)
 *     {
 *         $permissions = $listPermissions->handle(15); // 15 items per page
 *         return response()->json($permissions, 200);
 *     }
 * }
 * ```
 */
class ListPermissions
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
     * Retrieves a paginated list of permissions.
     *
     * Each permission is transformed into a PermissionDTO for the
     * presentation layer.
     *
     * @param int $perPage Number of items per page. Default is 10.
     * @return LengthAwarePaginator Paginated collection of PermissionDTOs.
     */
    public function handle(int $perPage = 10): LengthAwarePaginator
    {
        $paginator = $this->repo->query()
                                ->orderBy('id', 'asc')
                                ->paginate($perPage);

        // Transform each model to a DTO
        $paginator->getCollection()->transform(fn($permission) => PermissionDTO::fromModel($permission));

        return $paginator;
    }
}
