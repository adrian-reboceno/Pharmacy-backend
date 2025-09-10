<?php
#app/Services/V1/PermissionService.php
namespace App\Services\V1;

use App\DTOs\V1\Permission\PermissionDTO;
use App\Repositories\V1\Permission\PermissionRepository;
use App\Exceptions\V1\Permission\PermissionException;
use Spatie\Permission\Models\Permission;

class PermissionService
{
    public function __construct(
        private PermissionRepository $repository
    ) {}

    public function list(int $perPage = 15)
    {
       return $this->repository->all($perPage);
    }

    public function create(PermissionDTO $dto): Permission
    {
        if (Permission::where('name', $dto->name)->exists()) {
            throw PermissionException::alreadyExists($dto->name);
        }

        return $this->repository->create([
            'name' => $dto->name,
            'guard_name' => $dto->guard_name,
        ]);
    }

    public function find(int $id): Permission
    {
        $permission = $this->repository->find($id);

        if (! $permission) {
            throw PermissionException::notFound($id);
        }

        return $permission;
    }

    public function update(int $id, PermissionDTO $dto): Permission
    {
        $permission = $this->find($id);
        if (Permission::where('name', $dto->name)->exists()) {
            throw PermissionException::alreadyExists($dto->name);
        }

        return $this->repository->update($permission, [
            'name' => $dto->name,
            'guard_name' => $dto->guard_name,
        ]);
    }

    public function delete(int $id): bool
    {
        $permission = $this->find($id);

        return $this->repository->delete($permission);
    }
}
