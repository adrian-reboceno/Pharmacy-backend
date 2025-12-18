<?php
# src/Application/Role/UseCases/V1/DeleteRole.php

namespace App\Application\Role\UseCases\V1;

use App\Domain\Role\Repositories\RoleRepositoryInterface;
use App\Domain\Role\ValueObjects\RoleId;
use App\Shared\Domain\Exceptions\NotFoundException;

/**
 * Use Case: DeleteRole
 */
final class DeleteRole
{
    public function __construct(
        private readonly RoleRepositoryInterface $repository
    ) {}

    public function execute(int|string $id): void
    {
        $roleId = new RoleId((int) $id);
        $role   = $this->repository->findById($roleId);

        if ($role === null) {
            throw new NotFoundException("Role with ID {$roleId->value()} not found.");
        }

        $this->repository->delete($roleId);
    }
}
