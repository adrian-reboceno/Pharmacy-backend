<?php

// app/Application/Role/UseCases/V1/UpdateRole.php

namespace App\Application\Role\UseCases\V1;

use App\Application\Role\DTOs\V1\UpdateRoleDTO;
use App\Domain\Role\Repositories\RoleRepositoryInterface;
use App\Presentation\DTOs\V1\RoleDTO;
use Symfony\Component\HttpFoundation\Response;

/**
 * Use Case: Update an existing role's permissions in the system.
 *
 * This use case encapsulates the business logic required to update
 * a role's **permissions** (and optionally `guard_name`).
 * **The role's name cannot be updated** to ensure consistency across the system.
 *
 * Validates that all provided permissions exist before updating.
 * Throws a RuntimeException (409 Conflict) if any invalid permissions are provided.
 *
 * Applied principles:
 * - **SRP (Single Responsibility Principle):** Responsible only for updating role permissions.
 * - **DIP (Dependency Inversion Principle):** Depends on an abstraction
 *   (`RoleRepositoryInterface`) instead of a concrete implementation.
 *
 * Example usage in a Controller:
 * ```php
 * use App\Application\Role\UseCases\V1\UpdateRole;
 * use App\Application\Role\DTOs\V1\UpdateRoleDTO;
 *
 * class RoleController
 * {
 *     public function update(int $id, Request $request, UpdateRole $updateRole)
 *     {
 *         $dto = new UpdateRoleDTO(
 *             guard_name: $request->input('guard_name'),
 *             permissions: $request->input('permissions', [])
 *         );
 *
 *         $roleDTO = $updateRole->handle($id, $dto);
 *
 *         return response()->json($roleDTO, 200);
 *     }
 * }
 * ```
 */
class UpdateRole
{
    /**
     * Role repository for domain persistence operations.
     */
    protected RoleRepositoryInterface $repo;

    /**
     * Constructor.
     *
     * @param  RoleRepositoryInterface  $repo  Repository handling role persistence.
     */
    public function __construct(RoleRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Executes the update of a role's permissions (and optionally guard_name) by its ID.
     *
     * @param  int  $id  Unique identifier of the role to be updated.
     * @param  UpdateRoleDTO  $dto  Transfer object containing updated role data.
     * @return RoleDTO Data Transfer Object representing the updated role.
     *
     * @throws \RuntimeException If any provided permission does not exist (409 Conflict).
     */
    public function handle(int $id, UpdateRoleDTO $dto): RoleDTO
    {
        $dataToUpdate = [];

        // Only update permissions if provided in the DTO

        // Validate permissions
        if (! empty($dto->permissions)) {
            $validation = $this->repo->validatePermissions($dto->permissions);

            if (! empty($validation['invalid'])) {
                throw new \RuntimeException(
                    'The following permissions do not exist: '.implode(', ', $validation['invalid']),
                    Response::HTTP_CONFLICT // 409
                );
            }

            // Only pass valid permissions to the repository
            $dataToUpdate['permissions'] = $validation['valid'];
        }

        // Update the role with allowed fields only
        $role = $this->repo->update($id, $dataToUpdate);

        // Return DTO for the presentation layer
        return RoleDTO::fromModel($role);
    }
}
