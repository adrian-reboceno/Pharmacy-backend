<?php

# app/Infrastructure/Laboratory/Mappers/LaboratoryMapper.php

namespace App\Infrastructure\Laboratory\Mappers;

use App\Domain\Laboratory\Entities\Laboratory as DomainLaboratory;
use App\Domain\Laboratory\ValueObjects\LaboratoryId;
use App\Domain\Laboratory\ValueObjects\LaboratoryName;
use App\Domain\Laboratory\ValueObjects\LaboratoryCountry;
use App\Domain\Laboratory\ValueObjects\LaboratoryIsActive;
use App\Infrastructure\Laboratory\Models\Laboratory as EloquentLaboratory;

final class LaboratoryMapper
{
    public static function toDomain(EloquentLaboratory $model): DomainLaboratory
    {
        return new DomainLaboratory(
            id: new LaboratoryId($model->id),
            name: new LaboratoryName($model->name),
            country: new LaboratoryCountry($model->country),
            isActive: new LaboratoryIsActive((bool) $model->is_active),
        );
    }
}