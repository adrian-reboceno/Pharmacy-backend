<?php

namespace App\Infrastructure\Permission\Repositories;

use App\Domain\Permission\Entities\Permission as DomainPermission;
use App\Domain\Permission\Repositories\PermissionRepositoryInterface;
use App\Domain\Permission\ValueObjects\PermissionId;
use App\Domain\Permission\ValueObjects\PermissionName;
use App\Domain\Permission\ValueObjects\PermissionGuardName;
use App\Infrastructure\Permission\Models\Permission; // Eloquent (Spatie)

class PermissionRepository implements PermissionRepositoryInterface
{
    /**
     * Buscar un permiso por ID (Value Object).
     */
    public function findById(PermissionId $id): ?DomainPermission
    {
        $model = Permission::find($id->value());

        return $model ? $this->toDomain($model) : null;
    }

    /**
     * Buscar un permiso por nombre (Value Object).
     */
    public function findByName(PermissionName $name): ?DomainPermission
    {
        $model = Permission::where('name', $name->value())->first();

        return $model ? $this->toDomain($model) : null;
    }

    /**
     * Paginación simple que devuelve un array de DomainPermission.
     *
     * @return DomainPermission[]
     */
    public function paginate(int $page, int $perPage): array
    {
        $paginator = Permission::orderBy('id', 'asc')
            ->paginate($perPage, ['*'], 'page', $page);

        return array_map(
            fn ($model) => $this->toDomain($model),
            $paginator->items()
        );
    }

    /**
     * Total de permisos.
     */
    public function count(): int
    {
        return Permission::count();
    }

    /**
     * Guardar un permiso de dominio (crear o actualizar).
     */
    public function save(DomainPermission $permission): DomainPermission
    {
        $id = $permission->id()?->value(); // si tu VO permite null

        if ($id !== null) {
            $model = Permission::find($id) ?? new Permission();
        } else {
            $model = new Permission();
        }

        $model->name       = $permission->name()->value();
        $model->guard_name = $permission->guardName()->value();
        $model->save();

        return $this->toDomain($model);
    }

    /**
     * Eliminar un permiso por ID.
     */
    public function delete(PermissionId $id): void
    {
        Permission::where('id', $id->value())->delete();
    }

    /**
     * Mapper Eloquent → Dominio.
     */
    private function toDomain(Permission $model): DomainPermission
    {
        return new DomainPermission(
            new PermissionId($model->id),
            new PermissionName($model->name),
            new PermissionGuardName($model->guard_name)
        );
    }
}
