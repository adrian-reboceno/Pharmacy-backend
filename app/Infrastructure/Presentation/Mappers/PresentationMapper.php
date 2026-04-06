<?php
# app/Infrastructure/Presentation/Mappers/PresentationMapper.php

namespace App\Infrastructure\Presentation\Mappers;

use App\Domain\Presentation\Entities\Presentation as DomainPresentation;
use App\Domain\Presentation\ValueObjects\PresentationId;
use App\Domain\Presentation\ValueObjects\PresentationName;
use App\Domain\Presentation\ValueObjects\PresentationIsActive;
use App\Infrastructure\Presentation\Models\Presentation as EloquentPresentation;

final class PresentationMapper
{
    public static function toDomain(EloquentPresentation $model): DomainPresentation
    {
        return new DomainPresentation(
            id: new PresentationId($model->id),
            name: new PresentationName($model->name),
            isActive: new PresentationIsActive((bool)$model->is_active),
        );
    }
}