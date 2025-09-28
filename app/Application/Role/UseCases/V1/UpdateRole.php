<?php
# app/Application/Role/UseCases/V1/UpdateRole.php

namespace App\Application\Role\UseCases\V1;

use App\Domain\Role\Repositories\RoleRepositoryInterface;
use App\Application\Role\DTOs\V1\UpdateRoleDTO;
use App\Presentation\DTOs\V1\RoleDTO;
use Symfony\Component\HttpFoundation\Response;

/**
 * Use Case: Update an existing role in the system.
 *
 * This use case encapsulates the business logic required to update
 * a role while validating that no other role exists with the same
 * name and guard combination.
 *
 * Applied principles:
 * - **SRP (Single Responsibility Principle):** Responsible only for updating roles.
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
 *             name: $request->input('name'),
 *             guard_name: $request->input('guard_name')
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
     * Executes the update of a role by its ID.
     *
     * @param int $id Unique identifier of the role to be updated.
     * @param UpdateRoleDTO $dto Transfer object containing updated role data.
     *
     * @throws \RuntimeException If another role already exists with the same name and guard.
     *
     * @return RoleDTO Data Transfer Object representing the updated role.
     */
    public function handle(int $id, UpdateRoleDTO $dto): RoleDTO
    {
        // Check if another role already exists with the same name + guard
        // (ideally using a repository method like existsExceptId)
        $existing = $this->repo->exists($dto->name, $dto->guard_name);

        if ($existing) {
            throw new \RuntimeException(
                "A role with the name '{$dto->name}' already exists for guard '{$dto->guard_name}'.",
                Response::HTTP_CONFLICT // 409
            );
        }

        // Update the role in the domain layer
        $role = $this->repo->update($id, array_filter([
            'name'       => $dto->name,
            'guard_name' => $dto->guard_name,
        ]));

        // Return DTO for presentation layer
        return RoleDTO::fromModel($role);
    }
}
