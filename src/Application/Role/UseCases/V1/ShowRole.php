<?php
# src/Application/Role/UseCases/V1/ShowRole.php

namespace App\Application\Role\UseCases\V1;

use App\Domain\Role\Entities\Role;
use App\Domain\Role\Repositories\RoleRepositoryInterface;
use App\Domain\Role\ValueObjects\RoleId;
use App\Shared\Domain\Exceptions\NotFoundException;

/**
 * Use Case: ShowRole
 */
final class ShowRole
{
    public function __construct(
        private readonly RoleRepositoryInterface $repository
    ) {}

    /**
     * @param int|string $id
     */
    public function execute(int|string $id): Role
    {
        $roleId = new RoleId((int) $id);
        $role   = $this->repository->findById($roleId);

        if ($role === null) {
            throw new NotFoundException("Role with ID {$roleId->value()} not found.");
        }

        return $role;
    }
}
