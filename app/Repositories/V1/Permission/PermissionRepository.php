<?php

namespace App\Repositories\V1\Permission;

use Spatie\Permission\Models\Permission;

class PermissionRepository
{
    public function all()
    {
        return Permission::all();
    }

    public function find(int $id): ?Permission
    {
        return Permission::find($id);
    }

    public function create(array $data): Permission
    {
        return Permission::create($data);
    }

    public function update(Permission $permission, array $data): Permission
    {
        $permission->update($data);
        return $permission;
    }

    public function delete(Permission $permission): bool
    {
        return $permission->delete();
    }
}
