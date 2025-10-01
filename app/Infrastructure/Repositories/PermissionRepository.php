<?php
#app/Infrastructure/Repositories/PermissionRepository.php
namespace App\Infrastructure\Repositories;

use App\Domain\Permission\Repositories\PermissionRepositoryInterface;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Builder;

class PermissionRepository implements PermissionRepositoryInterface
{
    public function query(): Builder
    {
        return Permission::query();
    }

    public function find(int $id): ?object
    {
        return Permission::find($id);
    }

    public function create(array $data): object
    {
       
        return Permission::create($data);
    }

    public function update(int $id, array $data): object
    {
        $permission = $this->find($id);
        $permission->update($data);
        return $permission;
    }

    public function delete(int $id): bool
    {
        $permission = $this->find($id);
        return $permission ? $permission->delete() : false;
    }
    /**
     * Verifica si ya existe un permiso con ese nombre y guard (para Create)
     */
    public function exists(string $name, string $guard_name): bool
    {
        return $this->query()
                    ->where('name', $name)
                    ->where('guard_name', $guard_name)
                    ->exists();
    }
    // Método para chequear conflictos de name + guard_name excluyendo un ID específico
    public function existsExceptId(string $name, string $guard_name, int $exceptId): bool
    {
        return $this->query()
                    ->where('name', $name)
                    ->where('guard_name', $guard_name)
                    ->where('id', '!=', $exceptId)
                    ->exists();
    }
}
