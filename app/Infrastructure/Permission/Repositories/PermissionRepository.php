<?php

namespace App\Infrastructure\Permission\Repositories;

use App\Domain\Permission\Entities\Permission as DomainPermission;
use App\Domain\Permission\Repositories\PermissionRepositoryInterface;
use App\Domain\Permission\ValueObjects\PermissionId;
use App\Domain\Permission\ValueObjects\PermissionName;
use App\Infrastructure\Permission\Models\Permission; // Eloquent (extiende Spatie\Permission\Models\Permission)
use App\Infrastructure\Permission\Mappers\PermissionMapper;

class PermissionRepository implements PermissionRepositoryInterface
{
    /**
     * Buscar permiso por ID.
     */
    public function findById(PermissionId $id): ?DomainPermission
    {
        $model = Permission::find($id->value());

        return $model ? PermissionMapper::toDomain($model) : null;
    }

    /**
     * Buscar permiso por nombre.
     */
    public function findByName(PermissionName $name): ?DomainPermission
    {
        $model = Permission::where('name', $name->value())->first();

        return $model ? PermissionMapper::toDomain($model) : null;
    }

    /**
     * Paginación de permisos.
     *
     * Debe devolver array para cumplir la interfaz.
     *
     * @return DomainPermission[]
     */
    public function paginate(int $page, int $perPage, ?string $name = null): array
    {
        $paginator = Permission::where('name', 'LIKE', "%{$name}%")
            ->orderBy('id')
            ->paginate(
                perPage: $perPage,
                columns: ['*'],
                pageName: 'page',
                page: $page
            );

        // Devolvemos array de DomainPermission
        return PermissionMapper::toDomainArray($paginator->items());
    }

    /**
     * Total de permisos.
     */
    public function count(?string $name = null): int
    {
        return Permission::where('name', 'LIKE', "%{$name}%")->count();
    }

    /**
     * Guardar permiso (crear/actualizar).
     */
    public function save(DomainPermission $permission): DomainPermission
    {
        // Si la entidad ya tiene ID, intentamos buscar en BD
        $model = null;
        if ($permission->id() !== null) {
            $model = Permission::find($permission->id()->value());
        }

        if (! $model) {
            $model = new Permission();
        }

        $model->name       = $permission->name()->value();
        $model->guard_name = $permission->guardName()->value();
        $model->save();

        return PermissionMapper::toDomain($model);
    }

    /**
     * Eliminar permiso por ID.
     */
    public function delete(PermissionId $id): void
    {
        Permission::whereKey($id->value())->delete();
    }
}
