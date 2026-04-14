<?php
# app/Infrastructure/UnitOfMeasure/Mappers/UnitOfMeasureMapper.php

namespace App\Infrastructure\UnitOfMeasure\Mappers;

use App\Domain\UnitOfMeasure\Entities\UnitOfMeasure as DomainUnitOfMeasure;
use App\Domain\UnitOfMeasure\ValueObjects\UnitOfMeasureId;
use App\Domain\UnitOfMeasure\ValueObjects\UnitOfMeasureName;
use App\Domain\UnitOfMeasure\ValueObjects\UnitOfMeasureSymbol;
use App\Domain\UnitOfMeasure\ValueObjects\UnitOfMeasureIsActive;
use App\Infrastructure\UnitOfMeasure\Models\UnitOfMeasure as EloquentUnitOfMeasure;

final class UnitOfMeasureMapper
{
    public static function toDomain(EloquentUnitOfMeasure $model): DomainUnitOfMeasure
    {
        return new DomainUnitOfMeasure(
            id: new UnitOfMeasureId($model->id),
            name: new UnitOfMeasureName($model->name),
            symbol: new UnitOfMeasureSymbol($model->symbol),
            isActive: new UnitOfMeasureIsActive((bool)$model->is_active),
        );
    }
}