<?php
# app/Infrastructure/Role/Repositories/RoleRepository.php

namespace App\Infrastructure\Role\Repositories;

use App\Domain\Role\Entities\Role as DomainRole;
use App\Domain\Role\Repositories\RoleRepositoryInterface;
use App\Domain\Role\ValueObjects\RoleId;
use App\Domain\Role\ValueObjects\RoleName;
use App\Infrastructure\Role\Models\Role as EloquentRole;
use App\Infrastructure\Role\Mappers\RoleMapper;
use Illuminate\Support\Collection;

class RoleRepository implements RoleRepositoryInterface
{
    /**
     * Buscar rol por ID.
     */
    public function findById(RoleId $id): ?DomainRole
    {
        $model = EloquentRole::with('permissions')->find($id->value());

        return $model ? RoleMapper::toDomain($model) : null;
    }

    /**
     * Buscar rol por nombre.
     */
    public function findByName(RoleName $name): ?DomainRole
    {
        $model = EloquentRole::with('permissions')
            ->where('name', $name->value())
            ->first();

        return $model ? RoleMapper::toDomain($model) : null;
    }

    /**
     * Paginado de roles (Devuelve array<DomainRole>).
     */
    public function paginate(int $page, int $perPage): array
    {
        $paginator = EloquentRole::with('permissions')
            ->orderBy('id', 'asc')
            ->paginate($perPage, ['*'], 'page', $page);

        return RoleMapper::toDomainCollection($paginator->getCollection());
    }

    /**
     * Conteo total de roles.
     */
    public function count(): int
    {
        return EloquentRole::count();
    }

    /**
     * Guardar rol de dominio (crear o actualizar).
     */
    public function save(DomainRole $role): DomainRole
    {
        $id = $role->id()?->value(); // asumiendo Role::id() puede ser null en nuevos

        if ($id) {
            $model = EloquentRole::find($id) ?? new EloquentRole();
        } else {
            $model = new EloquentRole();
        }

        $model->name       = $role->name()->value();
        $model->guard_name = $role->guardName()->value();
        $model->save();

        // Si la entidad expone un array de permisos (strings)
        if (method_exists($role, 'permissions')) {
            $permissions = $role->permissions(); // array de nombres
            $model->syncPermissions($permissions);
        }

        return RoleMapper::toDomain($model);
    }

    /**
     * Eliminar rol por ID.
     */
    public function delete(RoleId $id): void
    {
        EloquentRole::where('id', $id->value())->delete();
    }
}
