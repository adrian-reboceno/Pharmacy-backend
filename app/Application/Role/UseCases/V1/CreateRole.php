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
 * This class encapsulates the application logic to register a new role,
 * ensuring that business rules such as uniqueness of the role name and guard,
 * and validity of permissions, are respected.
 *
 * Principles applied:
 * - **SRP (Single Responsibility Principle):** Handles only role creation logic.
 * - **DIP (Dependency Inversion Principle):** Relies on the domain-defined
 *   `RoleRepositoryInterface`, decoupling it from infrastructure details.
 *
 * Typical usage in a Controller:
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
 *             guard_name: $request->input('guard_name'),
 *             permissions: $request->input('permissions', [])
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
     * Repository responsible for persisting roles.
     *
     * @var RoleRepositoryInterface
     */
    protected RoleRepositoryInterface $repo;

    /**
     * Constructor.
     *
     * @param RoleRepositoryInterface $repo Repository handling role persistence operations.
     */
    public function __construct(RoleRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Handles the role creation workflow.
     *
     * Steps:
     * 1. Validates that the role name and guard combination does not already exist.
     * 2. Validates the provided permissions (if any).
     * 3. Creates the new role and synchronizes permissions.
     * 4. Returns a RoleDTO for presentation purposes.
     *
     * @param CreateRoleDTO $dto Data Transfer Object containing the role details.
     *
     * @throws \RuntimeException If:
     * - A role with the same name and guard already exists (HTTP 409 Conflict).
     * - Invalid permissions are provided (HTTP 409 Conflict).
     *
     * @return RoleDTO Data Transfer Object representing the newly created role.
     */
    public function handle(CreateRoleDTO $dto): RoleDTO
    {
        // 1. Validate uniqueness of role name + guard
        if ($this->repo->exists($dto->name, $dto->guard_name)) {
            throw new \RuntimeException(
                "A role with the name '{$dto->name}' already exists for guard '{$dto->guard_name}'.",
                Response::HTTP_CONFLICT // 409 Conflict
            );
        }

        // 2. Validate permissions (if provided)
        if (!empty($dto->permissions)) {
            $validation = $this->repo->validatePermissions($dto->permissions);

            if (!empty($validation['invalid'])) {
                throw new \RuntimeException(
                    'The following permissions do not exist: ' . implode(', ', $validation['invalid']),
                    Response::HTTP_CONFLICT // 409 Conflict
                );
            }

            // Keep only valid permissions
            $dto->permissions = $validation['valid'];
        }

        // 3. Create role in the repository
        $role = $this->repo->create([
            'name'        => $dto->name,
            'guard_name'  => $dto->guard_name,
            'permissions' => $dto->permissions,
        ]);

        // 4. Return DTO to presentation layer
        return RoleDTO::fromModel($role);
    }
}
