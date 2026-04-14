<?php
# src/Application/UnitOfMeasure/UseCases/V1/CreateUnitOfMeasure.php

namespace App\Application\UnitOfMeasure\UseCases\V1;

use App\Application\UnitOfMeasure\DTOs\V1\CreateUnitOfMeasureDTO;
use App\Domain\UnitOfMeasure\Entities\UnitOfMeasure;
use App\Domain\UnitOfMeasure\Repositories\UnitOfMeasureRepositoryInterface;
use App\Domain\UnitOfMeasure\ValueObjects\UnitOfMeasureId;
use App\Domain\UnitOfMeasure\ValueObjects\UnitOfMeasureName;
use App\Domain\UnitOfMeasure\ValueObjects\UnitOfMeasureSymbol;
use App\Domain\UnitOfMeasure\ValueObjects\UnitOfMeasureIsActive;
use App\Shared\Domain\Exceptions\AlreadyExistsException;

final class CreateUnitOfMeasure
{
    public function __construct(
        private readonly UnitOfMeasureRepositoryInterface $unitsOfMeasure
    ) {}

    public function execute(CreateUnitOfMeasureDTO $dto): UnitOfMeasure
    {
        $existing = $this->unitsOfMeasure->findByName($dto->name);
        if ($existing !== null) {
            throw new AlreadyExistsException('A unit of measure with this name already exists.');
        }

        $unitOfMeasure = new UnitOfMeasure(
            id: null,
            name: new UnitOfMeasureName($dto->name),
            symbol: new UnitOfMeasureSymbol($dto->symbol),
            isActive: new UnitOfMeasureIsActive($dto->isActive),
        );

        return $this->unitsOfMeasure->save($unitOfMeasure);
    }
}
