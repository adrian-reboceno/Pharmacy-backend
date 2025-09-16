<?php

namespace App\Repositories\V1\Permission;

use Spatie\Permission\Models\Permission;

class PermissionRepository
{
    public function all(int $perPage = 15, array $filters = [])
    {
        $query = Permission::query();

        foreach ($filters as $filter) {
            $field = $filter['field'] ?? null;
            $operator = $filter['operator'] ?? null;
            $value = $filter['value'] ?? null;

            if ($field && $operator && $value !== null) {
                match($operator) {
                    'contains' => $query->where($field, 'like', "%{$value}%"),
                    'equals' => $query->where($field, $value),
                    default => null
                };
            }
        }

        return $query->orderBy('id', 'asc')->paginate($perPage);
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
