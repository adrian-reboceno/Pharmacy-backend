<?php
# app/Application/Role/UseCases/V1/CreateRole.php

namespace App\Application\Role\UseCases\V1;

use App\Domain\Role\Repositories\RoleRepositoryInterface;
use App\Application\Role\DTOs\V1\CreateRoleDTO;
use App\Presentation\DTOs\V1\RoleDTO;
use Illuminate\Http\Response;

/**
 * Use Case: Create a new role in the system.
 *
 * This use case encapsulates the business logic to register a new role
 * while ensuring there is no existing role with the same name and guard.
 *
 * Applied principles:
 * - **SRP (Single Responsibility Principle):** Responsible only for creating roles.
 * - **DIP (Dependency Inversion Principle):** Depends on an abstraction
 *   (`RoleRepositoryInterface`) instead of a concrete implementation.
 *
 * Example usage in a Controller:
 * ```php
 * use App\Application\Role\UseCases\V1\CreateRole;
 * use App\Application\Role\DTOs\V1\CreateRoleDTO;
 *
 * class RoleController
 * {
 *     public function store(Request $request, CreateRole $createRole)
 *     {
 *         $dto = new CreateRoleDTO(
 *             name: $request->input('name'),
 *             guard_name: $request->input('guard_name')
 *         );
 *
 *         $roleDTO = $createRole->handle($dto);
 *
 *         return response()->json($roleDTO, 201);
 *     }
 * }
 * ```
 */
class CreateRole
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
     * Executes the role creation process after validating it does not already exist.
     *
     * @param CreateRoleDTO $dto Transfer object with required role data.
     *
     * @throws \RuntimeException If a role with the same name and guard already exists.
     *
     * @return RoleDTO Data Transfer Object representing the newly created role.
     */
    public function handle(CreateRoleDTO $dto): RoleDTO
    {
        // Validate if a role with the same name and guard already exists
        $existing = $this->repo->exists($dto->name, $dto->guard_name);

        if ($existing) {
            throw new \RuntimeException(
                "A role with the name '{$dto->name}' already exists for guard '{$dto->guard_name}'.",
                Response::HTTP_CONFLICT // 409
            );
        }

        // Create the role in the domain layer
        $role = $this->repo->create([
            'name'       => $dto->name,
            'guard_name' => $dto->guard_name,
        ]);

        // Return DTO for presentation layer
        return RoleDTO::fromModel($role);
    }
}
