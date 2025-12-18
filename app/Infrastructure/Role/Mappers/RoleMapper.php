<?php
# app/Infrastructure/Role/Mappers/RoleMapper.php

namespace App\Infrastructure\Role\Mappers;

use App\Domain\Role\Entities\Role as DomainRole;
use App\Domain\Role\ValueObjects\RoleId;
use App\Domain\Role\ValueObjects\RoleName;
use App\Domain\Role\ValueObjects\RoleGuardName;
use App\Infrastructure\Role\Models\Role as EloquentRole;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * RoleMapper
 *
 * Responsable de convertir entre el modelo Eloquent Role
 * y la entidad de dominio Role.
 */
final class RoleMapper
{
    /**
     * Eloquent → Domain.
     */
    public static function toDomain(EloquentRole $model): DomainRole
    {
        // Asumo entidad Domain\Role\Entities\Role con:
        // __construct(RoleId $id, RoleName $name, RoleGuardName $guardName, array $permissions = [])
        $permissions = $model->permissions()->pluck('name')->toArray();

        return new DomainRole(
            new RoleId($model->id),
            new RoleName($model->name),
            new RoleGuardName($model->guard_name),
            $permissions
        );
    }

    /**
     * Collection<EloquentRole> → array<DomainRole>.
     */
    public static function toDomainCollection(Collection $collection): array
    {
        return $collection
            ->map(fn (EloquentRole $model) => self::toDomain($model))
            ->values()
            ->all();
    }

    /**
     * Paginator<EloquentRole> → Paginator<DomainRole>.
     *
     * Útil si quieres seguir usando LengthAwarePaginator en presentación.
     */
    public static function toDomainPaginator(LengthAwarePaginator $paginator): LengthAwarePaginator
    {
        $items = $paginator->getCollection()
            ->map(fn (EloquentRole $model) => self::toDomain($model));

        return new LengthAwarePaginator(
            $items,
            $paginator->total(),
            $paginator->perPage(),
            $paginator->currentPage(),
            ['path' => $paginator->path()]
        );
    }
}
