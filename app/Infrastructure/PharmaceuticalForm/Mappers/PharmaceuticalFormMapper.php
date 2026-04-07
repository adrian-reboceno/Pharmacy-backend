<?php

# app/Infrastructure/PharmaceuticalForm/Mappers/PharmaceuticalFormMapper.php

namespace App\Infrastructure\PharmaceuticalForm\Mappers;

use App\Domain\PharmaceuticalForm\Entities\PharmaceuticalForm as DomainPharmaceuticalForm;
use App\Domain\PharmaceuticalForm\ValueObjects\PharmaceuticalFormId;
use App\Domain\PharmaceuticalForm\ValueObjects\PharmaceuticalFormName;
use App\Domain\PharmaceuticalForm\ValueObjects\PharmaceuticalFormIsActive;
use App\Infrastructure\PharmaceuticalForm\Models\PharmaceuticalForm as EloquentPharmaceuticalForm;

final class PharmaceuticalFormMapper
{
    public static function toDomain(EloquentPharmaceuticalForm $model): DomainPharmaceuticalForm
    {
        return new DomainPharmaceuticalForm(
            id: new PharmaceuticalFormId($model->id),
            name: new PharmaceuticalFormName($model->name),
            isActive: new PharmaceuticalFormIsActive((bool)$model->is_active),
        );
    }

 
}