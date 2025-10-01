<?php
#app/Application/Permission/UseCases/V1/UpdatePermission.php
namespace App\Application\Permission\UseCases\V1;

use App\Domain\Permission\Repositories\PermissionRepositoryInterface;
use App\Application\Permission\DTOs\V1\UpdatePermissionDTO;
use App\Presentation\DTOs\V1\PermissionDTO;
use Symfony\Component\HttpFoundation\Response;

/**
 * Use Case: Update an existing permission in the system.
 *
 * This use case encapsulates the business logic required to update
 * a permission while validating that no other permission exists
 * with the same name and guard combination.
 *
 * Applied principles:
 * - **SRP (Single Responsibility Principle):** Responsible only for updating permissions.
 * - **DIP (Dependency Inversion Principle):** Depends on an abstraction
 *   (`PermissionRepositoryInterface`) instead of a concrete implementation.
 *
 * Example usage in a Controller:
 * ```php
 * use App\Application\Permission\UseCases\V1\UpdatePermission;
 * use App\Application\Permission\DTOs\V1\UpdatePermissionDTO;
 *
 * class PermissionController
 * {
 *     public function update(int $id, Request $request, UpdatePermission $updatePermission)
 *     {
 *         $dto = new UpdatePermissionDTO([
 *             'name' => $request->input('name'),
 *             'guard_name' => $request->input('guard_name'),
 *         ]);
 *
 *         $permissionDTO = $updatePermission->handle($id, $dto);
 *         return response()->json($permissionDTO, 200);
 *     }
 * }
 * ```
 */
class UpdatePermission
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
     * Executes the update of a permission by its ID.
     *
     * @param int $id Unique identifier of the permission to be updated.
     * @param UpdatePermissionDTO $dto Transfer object containing updated permission data.
     *
     * @throws \RuntimeException If another permission already exists with the same name and guard.
     *
     * @return PermissionDTO Data Transfer Object representing the updated permission.
     */
    public function handle(int $id, UpdatePermissionDTO $dto): PermissionDTO
    {
        // Validate if another permission with the same name and guard already exists
        $existing = $this->repo->exists($dto->name, $dto->guard_name);

        if ($existing) {
            throw new \RuntimeException(
                "A permission with the name '{$dto->name}' already exists for guard '{$dto->guard_name}'.",
                Response::HTTP_CONFLICT // 409
            );
        }

        // Update the permission in the repository
        $permission = $this->repo->update($id, array_filter([
            'name'       => $dto->name,
            'guard_name' => $dto->guard_name,
        ]));

        // Return DTO for presentation layer
        return PermissionDTO::fromModel($permission);
    }
}
