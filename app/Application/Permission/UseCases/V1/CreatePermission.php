<?php
# app/Application/Permission/UseCases/V1/CreatePermission.php
namespace App\Application\Permission\UseCases\V1;

use App\Domain\Permission\Repositories\PermissionRepositoryInterface;
use App\Application\Permission\DTOs\V1\CreatePermissionDTO;
use App\Presentation\DTOs\V1\PermissionDTO;
use Illuminate\Http\Response;

/**
 * Use Case: Create a new permission in the system.
 *
 * This use case encapsulates the business logic to register a new permission,
 * ensuring there is no existing permission with the same name and guard.
 *
 * Applied principles:
 * - **SRP (Single Responsibility Principle):** Responsible only for creating permissions.
 * - **DIP (Dependency Inversion Principle):** Depends on an abstraction
 *   (`PermissionRepositoryInterface`) instead of a concrete implementation.
 *
 * Example usage in a Controller:
 * ```php
 * use App\Application\Permission\UseCases\V1\CreatePermission;
 * use App\Application\Permission\DTOs\V1\CreatePermissionDTO;
 *
 * class PermissionController
 * {
 *     public function store(Request $request, CreatePermission $createPermission)
 *     {
 *         $dto = new CreatePermissionDTO([
 *             'name'       => $request->input('name'),
 *             'guard_name' => $request->input('guard_name')
 *         ]);
 *
 *         $permissionDTO = $createPermission->handle($dto);
 *
 *         return response()->json($permissionDTO, 201);
 *     }
 * }
 * ```
 */
class CreatePermission
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
     * Executes the permission creation process after validating uniqueness.
     *
     * @param CreatePermissionDTO $dto Transfer object with required permission data.
     *
     * @throws \RuntimeException If a permission with the same name and guard already exists.
     *
     * @return PermissionDTO Data Transfer Object representing the newly created permission.
     */
    public function handle(CreatePermissionDTO $dto): PermissionDTO
    {
        // Check if a permission with the same name and guard already exists
        $existing = $this->repo->exists($dto->name, $dto->guard_name);

        if ($existing) {
            throw new \RuntimeException(
                "A permission with the name '{$dto->name}' already exists for guard '{$dto->guard_name}'.",
                Response::HTTP_CONFLICT // 409
            );
        }

        // Create the permission in the domain layer
        $permission = $this->repo->create([
            'name'       => $dto->name,
            'guard_name' => $dto->guard_name,
        ]);

        // Return DTO for presentation layer
        return PermissionDTO::fromModel($permission);
    }
}
