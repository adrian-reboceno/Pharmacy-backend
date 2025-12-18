<?php
# app/Infrastructure/Permission/Mappers/PermissionMapper.php

namespace App\Infrastructure\Permission\Mappers;

use App\Domain\Permission\Entities\Permission as DomainPermission;
use App\Domain\Permission\ValueObjects\PermissionId;
use App\Domain\Permission\ValueObjects\PermissionName;
use App\Domain\Permission\ValueObjects\PermissionGuardName;
use Illuminate\Database\Eloquent\Model;

/**
 * PermissionMapper
 *
 * Responsable de convertir entre el modelo Eloquent Permission
 * y la entidad de Dominio Permission.
 */
final class PermissionMapper
{
    /**
     * Convierte un modelo Eloquent a una entidad de Dominio.
     */
    public static function toDomain(Model $model): DomainPermission
    {
        return new DomainPermission(
            id: new PermissionId($model->id),
            name: new PermissionName($model->name),
            guardName: new PermissionGuardName($model->guard_name),
        );
    }

    /**
     * Convierte un conjunto de modelos (array|Collection) a array de DomainPermission.
     *
     * @param iterable<Model> $models
     * @return DomainPermission[]
     */
    public static function toDomainArray(iterable $models): array
    {
        $result = [];

        foreach ($models as $model) {
            $result[] = self::toDomain($model);
        }

        return $result;
    }
}
