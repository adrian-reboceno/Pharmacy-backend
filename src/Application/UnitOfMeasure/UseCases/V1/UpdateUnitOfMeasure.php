<?php
# src/Application/UnitOfMeasure/UseCases/V1/UpdateUnitOfMeasure.php

namespace Application\UnitOfMeasure\UseCases\V1;

use App\Application\UnitOfMeasure\DTOs\V1\UpdateUnitOfMeasureDTO;
use App\Domain\UnitOfMeasure\Entities\UnitOfMeasure;
use App\Domain\UnitOfMeasure\Repositories\UnitOfMeasureRepositoryInterface;
use App\Domain\UnitOfMeasure\ValueObjects\UnitOfMeasureId;
use App\Domain\UnitOfMeasure\ValueObjects\UnitOfMeasureName;
use App\Domain\UnitOfMeasure\ValueObjects\UnitOfMeasureSymbol;
use App\Domain\UnitOfMeasure\ValueObjects\UnitOfMeasureIsActive;
use App\Shared\Domain\Exceptions\NotFoundException;

final class UpdateUnitOfMeasure
{
    public function __construct(
        private readonly UnitOfMeasureRepositoryInterface $unitsOfMeasure
    ) {}

    public function execute(UpdateUnitOfMeasureDTO $dto): UnitOfMeasure
    {
        $unitOfMeasureId = new UnitOfMeasureId($dto->id);
        $unitOfMeasure   = $this->unitsOfMeasure->findById($unitOfMeasureId);

        if ($unitOfMeasure === null) {
            throw new NotFoundException("Unit of measure with ID {$dto->id} not found.");
        }

        if ($dto->name !== null) {
            $unitOfMeasure->rename(new UnitOfMeasureName($dto->name));
        }

        if ($dto->symbol !== null) {
            $unitOfMeasure->changeSymbol(new UnitOfMeasureSymbol($dto->symbol));
        }

        if ($dto->isActive !== null) {
            $unitOfMeasure->isActive()->value()
                ? $unitOfMeasure->deactivate()
                : $unitOfMeasure->activate();
        }

        return $this->unitsOfMeasure->save($unitOfMeasure);
    }
}
