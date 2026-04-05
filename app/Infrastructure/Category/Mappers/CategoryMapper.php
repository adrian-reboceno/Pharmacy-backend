<?php
# app/Infrastructure/Category/Mappers/CategoryMapper.php

namespace App\Infrastructure\Category\Mappers;

use App\Domain\Category\Entities\Category as DomainCategory;
use App\Domain\Category\ValueObjects\CategoryDescription;
use App\Domain\Category\ValueObjects\CategoryId;
use App\Domain\Category\ValueObjects\CategoryIsActive;
use App\Domain\Category\ValueObjects\CategoryName;
use App\Infrastructure\Category\Models\Category as EloquentCategory;

final class CategoryMapper
{
    public static function toDomain(EloquentCategory $model): DomainCategory
    {
        return new DomainCategory(
            id: new CategoryId($model->id),
            name: new CategoryName($model->name),
            description: $model->description !== null
                ? new CategoryDescription($model->description)
                : null,
            isActive: new CategoryIsActive((bool)$model->is_active),
        );
    }
}
