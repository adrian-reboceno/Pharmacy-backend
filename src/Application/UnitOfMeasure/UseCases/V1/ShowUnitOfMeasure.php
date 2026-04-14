<?php
# src/Application/UnitOfMeasure/UseCases/V1/ShowUnitOfMeasure.php

namespace App\Application\UnitOfMeasure\UseCases\V1;

use App\Domain\UnitOfMeasure\Entities\UnitOfMeasure;
use App\Domain\UnitOfMeasure\Repositories\UnitOfMeasureRepositoryInterface;
use App\Domain\UnitOfMeasure\ValueObjects\UnitOfMeasureId;
use App\Shared\Domain\Exceptions\NotFoundException;

final class ShowUnitOfMeasure
{
    public function __construct(
        private readonly UnitOfMeasureRepositoryInterface $unitsOfMeasure
    ) {}

    public function execute(int $id): UnitOfMeasure
    {
        $unitOfMeasureId = new UnitOfMeasureId($id);
        $unitOfMeasure   = $this->unitsOfMeasure->findById($unitOfMeasureId);

        if ($unitOfMeasure === null) {
            throw new NotFoundException("Unit of measure with ID {$id} not found.");
        }

        return $unitOfMeasure;
    }
}