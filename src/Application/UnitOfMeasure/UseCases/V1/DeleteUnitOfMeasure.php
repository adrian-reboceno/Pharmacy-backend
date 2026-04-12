<?php
# src/Application/UnitOfMeasure/UseCases/V1/DeleteUnitOfMeasure.php

namespace Application\UnitOfMeasure\UseCases\V1;


use App\Domain\UnitOfMeasure\Repositories\UnitOfMeasureRepositoryInterface;
use App\Domain\UnitOfMeasure\ValueObjects\UnitOfMeasureId;
use App\Shared\Domain\Exceptions\NotFoundException;

final class DeleteUnitOfMeasure
{
    public function __construct(
        private readonly UnitOfMeasureRepositoryInterface $unitsOfMeasure
    ) {}

    public function execute(int $id): void
    {
        $unitOfMeasureId = new UnitOfMeasureId($id);
        $unitOfMeasure   = $this->unitsOfMeasure->findById($unitOfMeasureId);

        if ($unitOfMeasure === null) {
            throw new NotFoundException("Unit of measure with ID {$id} not found.");
        }

        $unitOfMeasure->deactivate();
        $this->unitsOfMeasure->save($unitOfMeasure);
    }
}